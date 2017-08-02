<?php

/**
 * @author Pavel Stepanets <pahhan.ne@gmail.com>
 */
class Domstor_Domstor
{
    protected $object;
    protected $action;
    protected $server_name = 'domstor.ru';
    protected $api_path = '/gateway';
    
    /**
     *
     * @var Domstor_SortClient
     */
    protected $sort_client;

    /**
     *
     * @var SP_Helper_Pager
     */
    protected $pager;
    
    /**
     *
     * @var Domstor_Filter_Form
     */
    protected $filter;
    protected $filter_tmpl_dir;
    protected $detail_tmpl_dir;
    protected $params = array(); // Параметры списка (фильтры, сортировка  и т.д.)
    protected $my_id = 1; // Идентификатор организации
    protected $pagination_count = 15; // Количество ссылок на страницы
    protected $href_placeholders = array(
        'object' => '%object',
        'action' => '%action',
        'id' => '%id',
        'page' => '%page',
        'sort' => '%sort',
        'filter' => '%filter',
    );
    protected $href_tmpls = array(
        'list' => '?object=%object&action=%action&page=%page%sort%filter',
        'object' => '?object=%object&action=%action&id=%id%sort%filter',
        'page_part' => '',
        'flat_purchase' => '?object=flat&action=purchase&id=%id',
        'house_purchase' => '?object=house&action=purchase&id=%id',
        'commerce_sale' => '?object=commerce&action=sale&id=%id',
        'complex_sale' => '?object=complex&action=sale&id=%id',
    ); // Шаблоны ссылок
    protected $in_region = false; // Флаг определяет отображение некоторых столбцов списка
    protected $empty_list_message = '<p class="domstor_not_found">Объекты с данными параметрами не найдены</p>';
    protected $home_location;
    protected $filter_data_loader_config;

    /**
     *
     * @var Domstor_SiteMapGenerator
     */
    protected $site_map_generator;

    /**
     *
     * @var Doctrine_Cache_Interface
     */
    protected $cacheDriver;
    protected $cacheTime = 0;
    protected $cacheUniqKey = '';

    /**
     *
     * @var Domstor_DataProviderInterface
     */
    protected $mainDataProvider;
    
    /**
     *
     * @var Domstor_DataProviderInterface
     */
    protected $siteMapDataProvider;
    
    /**
     *
     * @var Domstor_List_ListFactoryInterface;
     */
    protected $listFactory;
    
    /**
     *
     * @var Domstor_Filter_FilterFactory;
     */
    protected $filterFactory;

    public static function checkObjectAction($object, $action)
    {
        return Domstor_Helper::checkEstateAction($object, $action);
    }

    public function __construct($orgId, $locationId)
    {
        $this->my_id = $orgId;
        $this->setHomeLocation($locationId);
        $this->sort_client = new Domstor_SortClient();
        $this->pager = new SP_Helper_Pager();
        $this->filter_data_loader_config = new Domstor_Filter_DataLoaderConfig();
        $this->filterFactory = new Domstor_Filter_FilterFactory();
    }
    
    
    public function setListFactory(Domstor_List_ListFactoryInterface $listFactory)
    {
        $this->listFactory = $listFactory;

        return $this;
    }
    
    public function setFilterFactory(Domstor_Filter_FilterFactory $filterFactory)
    {
        $this->filterFactory = $filterFactory;

        return $this;
    }
    
    /**
     *
     * @return type DomstorSiteMapGenerator
     */
    public function getSiteMapGenerator()
    {
        if (!$this->site_map_generator) {
            $this->site_map_generator = new Domstor_SiteMapGenerator($this->getHrefTemplate('object'));
        }

        return $this->site_map_generator;
    }

    // Добавляет элементы из входного массива в массив параметров
    protected function _addParamsFromRequest($params, $request_array, $keys)
    {
        foreach ($keys as $key => $value) {
            if (isset($request_array[$key])) {
                $params[$value] = $request_array[$key];
            }
        }
        return $params;
    }

    // Собирает url из параметров
    protected function _getUrlPartsFromRequest(&$request_array, $keys = array())
    {
        $out = '';
        $keys += array('ref_city', 'inreg');
        foreach ($keys as $key) {
            if (isset($request_array[$key])) {
                $out.= '&' . $key;
                if ($request_array[$key] !== '') {
                    $out.= '=' . $request_array[$key];
                }
            }
        }
        return $out;
    }

    // Подготавливаем параметры запроса к серверу
    protected function _prepareRequestParams(array $params)
    {
        // Добавляем id организации - обязательный параметр для всех запросов
        $params['aid'] = $this->my_id;
        $params = $this->_addParamsFromRequest($params, $_GET, array('ref_city' => 'ref_city'));
        $params = array_merge($this->params, $params);
        // Если в фильтре есть крупные города, то нужно убрать ref_city из запроса, иначе объекты в них не найдутся

        if ($this->getFilterDataLoaderConfig()->subregionsWithBig()
            and $this->hasParam('ref_city', $params)
            and $this->filter
            and $this->filter->getField('district')->getValue()) {
            unset($params['ref_city']);
        }
        return $params;
    }

    // Формируем url запроса к серверу для списка
    protected function _getListRequest(array $params)
    {
        // Получаем массив параметров запроса
        $params = $this->_prepareRequestParams($params);

        // Добавляем параметры характерные для списка
        $params['limit'] = $this->getPager()->get('on_page');
        if (isset($params['_no_limit_'])) {
            unset($params['limit'], $params['_no_limit_']);
        }

        $url = 'http://' . $this->server_name . $this->api_path . '/list/?' . http_build_query($params) . $this->sort_client->getRequestString();

        if ($this->filter) {
            $url.= $this->filter->getServerRequestString();
        }

        return $url;
    }

    // Формируем url запроса к серверу для списка
    protected function _getSitemapRequest(array $params)
    {
        // Получаем массив параметров запроса
        $params = $this->_prepareRequestParams($params);

        $url = 'http://' . $this->server_name . $this->api_path . '/site-map/?' . http_build_query($params);

        if ($this->filter) {
            $url.= $this->filter->getServerRequestString();
        }

        return $url;
    }

    // Формируем url запроса к серверу для количества
    protected function _getCountRequest(array $params)
    {
        // Получаем массив параметров запроса
        $params = $this->_prepareRequestParams($params);

        $url = 'http://' . $this->server_name . $this->api_path . '/count/?' . http_build_query($params);
        if ($this->filter) {
            $url.= $this->filter->getServerRequestString();
        }

        return $url;
    }

    // Формируем url запроса к серверу для объекта
    protected function _getObjectRequest(array $params)
    {
        // Получаем массив параметров запроса
        $params = $this->_prepareRequestParams($params);
        $url = 'http://' . $this->server_name . $this->api_path . '/object/?' . http_build_query($params) . $this->sort_client->getRequestString();
        if ($this->filter) {
            $url.= $this->filter->getServerRequestString();
        }

        //echo $url;
        return $url;
    }

    // Формируем url запроса к серверу для списка других городов
    protected function _getLocationsRequest(array $params)
    {
        // Получаем массив параметров запроса
        $params = $this->_prepareRequestParams($params);
        $this_params = array_merge($params, $this->params);
        unset($this_params['ref_city']);
        $url = 'http://' . $this->server_name . $this->api_path . '/org/locations/' . $this->my_id . '/' . $params['object'] . '/' . $params['action'] . '?' . http_build_query($this_params);
        return $url;
    }

    // Подготавливаем параметры для виджета списка
    protected function _prepareListParams(array $params)
    {
        $list_params = array();
        $list_params['in_region'] = $this->_isInRegion();
        $list_params['server_name'] = $this->server_name;
        $list_params['object_href'] = $this->_processObjectHref($params);
        $list_params['id_placeholder'] = $this->getHrefPlaceholder('id');
        $list_params['action'] = $params['action'];
        $list_params['empty_list_message'] = $this->empty_list_message;
        $list_params['sort'] = array(
            'uri' => $this->_processSortHref($params),
            'uri_part' => '&%name%=%desc%',
            'input' => $_GET,
        );
        $list_params['exchange_flat_href'] = $this->getHrefTEmplate('flat_purchase');
        $list_params['exchange_house_href'] = $this->getHrefTEmplate('house_purchase');
        return $list_params;
    }

    // Подготавливаем параметры для виджета объекта
    protected function _prepareObjectParams(array $params)
    {
        $object_params = array();
        $object_params['in_region'] = $this->_isInRegion();
        $object_params['server_name'] = $this->server_name;
        $object_params['object_href'] = $this->_processObjectHref($params);
        $object_params['id_placeholder'] = $this->getHrefPlaceholder('id');
        $object_params['_action'] = $params['action'];
        $object_params['action'] = ($params['action'] == 'rent' or $params['action'] == 'rentuse') ? 'rent' : 'sale';
        $object_params['exchange_flat_href'] = $this->getHrefTEmplate('flat_purchase');
        $object_params['exchange_house_href'] = $this->getHrefTEmplate('house_purchase');
        $object_params['commerce_href'] = $this->getHrefTemplate('commerce_sale');
        if ($this->detail_tmpl_dir) {
            $object_params['tmpl_dir'] = $this->getDetailTmplDir();
        }
        return $object_params;
    }

    // Заменяет метку %filter в строке на текущие праметры фильтра
    protected function _replaceFilterHref($href_tmpl)
    {
        if ($this->filter) {
            return $this->filter->replaceString('%filter', $href_tmpl);
        }
        return str_replace('%filter', '', $href_tmpl);
    }

    // Заменяет метку %sort в строке на текущие праметры фильтра
    protected function _replaceSortHref($href_tmpl)
    {
        if (strpos($href_tmpl, '%sort') !== false) {
            $data = $_GET;
            $out = '';
            if (is_array($data)) {
                foreach ($data as $key => $value) {
                    if (strpos($key, 'sort-') === 0) {
                        $out.= '&' . $key . '=' . $value;
                    }
                }
            }
            return str_replace('%sort', $out, $href_tmpl);
        } else {
            return $href_tmpl;
        }
    }

    // Обрабатывает определенный шаблон ссылки
    protected function _replaceObjectAction(array $params, $href_tmpl)
    {
        $keys[] = '%object';
        $values[] = $params['object'];
        $keys[] = '%action';
        $values[] = $params['action'];
        return str_replace($keys, $values, $href_tmpl);
    }

    // Формирует ссылку на объект
    protected function _processObjectHref(array $params)
    {
        $href = $this->_replaceObjectAction($params, $this->getHrefTemplate('object'));
        $href = $this->_replaceFilterHref($href);
        $href = $this->_replaceSortHref($href);
        $href = $href . $this->_getUrlPartsFromRequest($_GET);
        return rtrim($href, '?');
    }

    // Формирует ссылку на страницу списка
    protected function _processListHref(array $params)
    {
        $href = $this->_replaceObjectAction($params, $this->getHrefTemplate('list'));
        $href = $this->_replaceFilterHref($href);
        $href = $this->_replaceSortHref($href);
        $href = $href . $this->_getUrlPartsFromRequest($_GET);
        return rtrim($href, '?');
    }

    // Формирует ссылку на страницы списков других городов
    protected function _processLocationsHref(array $params)
    {
        $href = $this->_replaceObjectAction($params, $this->getHrefTemplate('list'));
        $href = str_replace(array('%filter', '%sort', '%page'), array('', '', '1'), $href);
        $href.= '&ref_city=%id';
        return $href;
    }

    // Формирует ссылку для сортировки списка
    protected function _processSortHref(array $params)
    {
        $href = $this->_replaceObjectAction($params, $this->getHrefTemplate('list'));
        $href = $this->_replaceFilterHref($href);
        $href = str_replace('%page', isset($params['page']) ? $params['page'] : '', $href);
        $href = $href . $this->_getUrlPartsFromRequest($_GET);
        return $href;
    }

    protected function _getLocationInfo($location_id)
    {
        $data = $this->read('/gateway/location/info/' . $location_id);
        return $data;
    }

    public function setCacheDriver(Doctrine_Cache_Interface $cache_driver)
    {
        $this->cacheDriver = $cache_driver;
    }

    public function setCacheTime($cache_time)
    {
        $this->cacheTime = $cache_time;
    }
    
    public function setCacheUniqKey($cacheUniqKey)
    {
        $this->cacheUniqKey = $cacheUniqKey;
    }

    public function createFilter($object, $action, array $filter_factory_params = array())
    {
        if (!isset($filter_factory_params['filter_dir'])) {
            $filter_factory_params['filter_dir'] = $this->filter_tmpl_dir;
        }
        $filter_factory_params['domstor'] = $this;
        $this->filter = $this->filterFactory->create($object, $action, $filter_factory_params);
        return $this->filter;
    }

    /**
     *
     * @param string $object
     * @param string $action
     * @param integer $page
     * @param array $params
     * @return Domstor_List_Common
     */
    public function getList($object, $action, $page, array $params = array())
    {
        // Упаковываем $object, $action и $page в параметры
        $params['object'] = $object;
        $params['action'] = $action;
        $params['page'] = $page ? $page : 1;

        $filter = $this->createFilter($object, $action);
        if ($filter) {
            $filter->bindFromRequest();
        }

        // Получаем url запроса на основе параметров
        $url = $this->_getListRequest($params);

        // Получаем данные
        $data = $this->_getData($url);

        // Последний элемент - общее число объектов
        $total = array_pop($data);

        // Создаем фабрику списков
        $factory = new Domstor_List_ListFactory();

        // Получаем параметры для списка
        $list_params = $this->_prepareListParams($params);
        $list_params['data'] = $data;

        // Фабрика создает список
        $list = $factory->create($object, $action, $list_params);

        // Создаем объект pager постраничного вывода
        $this->pager->init(array(
            'total' => $total,
            'pager_count' => $this->pagination_count,
            'href_tmpl' => $this->_processListHref($params),
            'href_page_part' => $this->getHrefTemplate('page_part'),
            'link_tmpl' => '<a class="domstor_pagination_link" href="%href">%text</a> ',
            'layout_tmpl' => '<div class="domstor_pagination"><p>%info%text</p></div>',
            'current_page_tmpl' => '<span class="domstor_pagination_selected">%text</span> ',
            'current' => $page,
        ));

        $list->setPager($this->pager);
        $list->setFilter($filter);

        return $list;
    }

    public function getObject($object, $action, $id, array $params = array())
    {
        return $this->getDetail($object, $action, $id, $params);
    }

    /**
     *
     * @param string $object
     * @param string $action
     * @param string $id
     * @param array $params
     * @return false|Domstor_Detail_Supply
     */
    public function getDetail($object, $action, $id, array $params = array())
    {
        $params['object'] = $object;
        $params['action'] = $action;
        $params['oid'] = $id;

        $filter = $this->createFilter($object, $action);
        if ($filter) {
            $filter->bindFromRequest();
        }

        // Получаем url запроса на основе параметров
        $url = $this->_getObjectRequest($params);

        // Получаем данные
        $data = $this->_getData($url);
        if (!isset($data['id'])) {
            return null;
        }

        // Создаем фабрику объектов
        $factory = new Domstor_Detail_DetailFactory;

        // Получаем параметры для списка
        $object_params = $this->_prepareObjectParams($params);
        //$object_params['object'] = $data;
        // Фабрика создает объект
        $obj = $factory->create($object, $action, $object_params);
        $obj->setData($data);
        return $obj;
    }

    public function getCount($object, $action, $params = array())
    {
        $params['object'] = $object;
        $params['action'] = $action;
        $url = $this->_getCountRequest($params);
        $cache_time = isset($params['cache']) ? $params['cache'] : null;
        $data = $this->_getData($url, $cache_time);
        return $data[0];
    }
    
    public function getAllCounts(array $settings = array(), array $params = array())
    {
        $result = array();
        $s = array_replace(array(
            'living' => true,
            'commerce' => true,
            'with_empty' => false,
        ), $settings);

        if ($s['living']) {
            foreach (Domstor_Helper::getLivingTypes() as $object) {
                foreach (Domstor_Helper::getActions($object) as $action) {
                    $c = $this->getCount($object, $action, $params);
                    if ($c > 0 || $s['with_empty']) {
                        $result[$object][$action] = $c;
                    }
                }
            }
        }
            
        if ($s['commerce']) {
            foreach (Domstor_Helper::getCommerceTypes() as $object) {
                $result[$object] = array();
                foreach (Domstor_Helper::getActions($object) as $action) {
                    $c = $this->getCount($object, $action, $params);
                    if ($c > 0 || $s['with_empty']) {
                        $result[$object][$action] = $c;
                    }
                }
            }
        }

        return $result;
    }

    public function generateSiteMap($object, $action, array $params = array())
    {
        // Упаковываем $object, $action и $page в параметры
        $params['object'] = $object;
        $params['action'] = $action;

        //		$filter = $this->createFilter($object, $action);
        //		if( $filter )
        //		{
        //			$filter->bindFromRequest();
        //		}
        // Получаем url запроса на основе параметров
        $url = $this->_getSitemapRequest($params);
        //echo $url, '<br>';
        
        $urlReader = new Domstor_UrlReader();
        $this->siteMapDataProvider = new Domstor_JsonDataProvider(new Domstor_CacheUrlReader($urlReader, new Doctrine_Cache_Array(), 3000));
        $data = $this->siteMapDataProvider->getData($url);

        //print_r($data);

        $this->getSiteMapGenerator()->setData($data)->setRequestUrl($url);
        $this->getSiteMapGenerator()->generate();
    }

    public function getLocationsList($object, $action, array $params = array())
    {
        //if( !$this->home_location ) return FALSE;
        $params['object'] = $object;
        $params['action'] = $action;
        $params['location'] = $this->home_location;
        $url = $this->_getLocationsRequest($params);
        $data = $this->_getData($url);
        $current_id = $this->getRealParam('ref_city');
        unset($data[$current_id]);
        $tmpl = $this->_processLocationsHref($params);
        $list = new Domstor_LocationsList($data, $tmpl, $this->home_location);
        return $list;
    }

    public function displayLocationsList($object, $action, $prefix = null, array $params = array())
    {
        if ($list = $this->getLocationsList($object, $action, $params)) {
            echo $list->display($prefix);
        }
    }

    public function getLocationName($pad = 'im')
    {
        $id = isset($_GET['ref_city']) ? $_GET['ref_city'] : $this->home_location;
        $data = $this->read('/gateway/location/name/' . $id . '/' . $pad, false);
        return $data[0];
    }

    public function displayFilter($object, $action, $return = false)
    {
        if (is_null($this->filter)) {
            $this->createFilter($object, $action);
        }
        if ($this->filter) {
            if ($return) {
                return $this->filter->render();
            }
            $this->filter->display();
        }
    }

    public function setMyId($value)
    {
        $this->my_id = (int) $value;
        return $this;
    }

    public function getMyId()
    {
        return $this->my_id;
    }

    public function setFilterTmplDir($value)
    {
        $this->filter_tmpl_dir = $value;
        return $this;
    }

    public function getFilterTmplDir()
    {
        return $this->filter_tmpl_dir;
    }

    public function getDetailTmplDir()
    {
        return $this->detail_tmpl_dir;
    }

    public function setDetailTmplDir($detail_tmpl_dir)
    {
        $this->detail_tmpl_dir = $detail_tmpl_dir;
    }

    public function setHomeLocation($value)
    {
        if (is_null($value)) {
            $this->home_location = null;
            $this->deleteParam('ref_city');
        } else {
            $this->home_location = (integer) $value;
            $this->addParam('ref_city', $this->home_location);
        }
    }

    public function setServerName($value)
    {
        $this->server_name = $value;
        return $this;
    }

    public function getServerName()
    {
        return $this->server_name;
    }

    public function getFilter($object = null, $action = null)
    {
        if (!$this->filter) {
            if ($object and $action) {
                $this->createFilter($object, $action);
            }
        }
        return $this->filter;
    }

    public function setParams(array $value)
    {
        $this->params = $value;
        return $this;
    }

    public function deleteParam($name)
    {
        if ($this->hasParam($name)) {
            unset($this->params[$name]);
        }
    }

    public function addParams(array $value)
    {
        $this->params = array_merge($this->params, $value);
        return $this;
    }

    public function addParam($name, $value)
    {
        $this->params[$name] = $value;
        return $this;
    }

    public function hasParam($name, array $params = array())
    {
        if (!count($params)) {
            $params = &$this->params;
        }
        return array_key_exists($name, $params);
    }

    public function getParam($name)
    {
        if ($this->hasParam($name)) {
            return $this->params[$name];
        }
    }

    public function getParams()
    {
        return $this->params;
    }

    public function clearParams()
    {
        $this->params = array();
        return $this;
    }

    public function getRealParam($name)
    {
        if (isset($_GET[$name])) {
            return $_GET[$name];
        }
        return $this->getParam($name);
    }

    public function setDefaultSort(array $sort)
    {
        $this->getSortClient()->setDefault($sort);
        return $this;
    }

    public function clearDefaultSort()
    {
        $this->setDefaultSort(array());
        return $this;
    }

    public function setExposition($days)
    {
        if ($days == 0) {
            unset($this->params['edit_dt']);
            return $this;
        }

        $seconds = strtotime(date('Y-m-d')) - $days * 86400;
        $this->addParam('edit_dt', array('min' => date('Y-m-d', $seconds)));
        return $this;
    }

    public function read($uri)
    {
        return $this->_getData('http://' . $this->getServerName() . $uri);
    }

    protected function _getData($url, $cache = null)
    {
        if ($this->mainDataProvider === null) {
            $urlReader = $this->cacheDriver === null?
                new Domstor_UrlReader() :
                new Domstor_CacheUrlReader(new Domstor_UrlReader(), $this->cacheDriver, $this->cacheTime, $this->cacheUniqKey);
            $this->mainDataProvider = new Domstor_StdDataProvider($urlReader);
        }
        
        return $this->mainDataProvider->getData($url);
    }

    public function getSortClient()
    {
        return $this->sort_client;
    }

    /**
     *
     * @return SP_Helper_Pager
     */
    public function getPager()
    {
        return $this->pager;
    }

    public function setHrefTemplate($name, $value)
    {
        if (!array_key_exists($name, $this->href_tmpls)) {
            throw new Exception(sprintf(
                'Unavailable template "%s", available templates are: %s',
                $name,
                implode(', ', array_keys($this->href_tmpls))
            ));
        }
        $this->href_tmpls[$name] = $value;
        return $this;
    }

    public function getHrefTemplate($name)
    {
        return $this->href_tmpls[$name];
    }
    
    public function setHrefPlaceholder($name, $value)
    {
        if (!array_key_exists($name, $this->href_placeholders)) {
            throw new Exception(sprintf(
                'Unavailable placeholder "%s", available placeholders are: %s',
                $name,
                implode(', ', array_keys($this->href_placeholders))
            ));
        }
        $this->href_placeholders[$name] = $value;
        return $this;
    }
    
    public function getHrefPlaceholder($name)
    {
        return $this->href_placeholders[$name];
    }

    public function setEmptyListMessage($value)
    {
        $this->empty_list_message = $value;
    }

    protected function _isInRegion()
    {
        if (isset($_GET['inreg'])) {
            return true;
        }
        $ref_city_param = $this->getRealParam('ref_city');
        $loc_id = $ref_city_param ? $ref_city_param : $this->home_location;
        if (!$loc_id) {
            return $this->in_region;
        }
        $info = $this->_getLocationInfo($loc_id);
        if (isset($info['type'])) {
            return $info['type'] == '2';
        }
    }

    public function inRegion($value = null)
    {
        if (is_null($value)) {
            return $this->_isInRegion();
        }

        $this->in_region = (bool) $value;
        return $this;
    }

    public function check($object, $action)
    {
        return Domstor_Helper::checkEstateAction($object, $action);
    }

    public function setPaginationOnPage($value)
    {
        $value = (int) $value;
        if ($value > 0) {
            $this->getPager()->set('on_page', $value);
        }
    }

    public function setPaginationCount($pagination_count)
    {
        $this->pagination_count = $pagination_count;
    }

    public function getListLink($object, $action)
    {
        $page = $this->loadPageNumber();
        $link = $this->_processListHref(array('object' => $object, 'action' => $action));
        $link = str_replace('%page', $page, $link);
        return $link;
    }

    public function savePageNumber($val)
    {
        if (!isset($_SESSION)) {
            $started = @session_start();
            if (!$started) {
                return $this;
            }
        }
        $_SESSION['domstor_from_page'] = $val;
        return $this;
    }

    public function loadPageNumber()
    {
        if (!isset($_SESSION)) {
            session_start();
        }
        $page = isset($_SESSION['domstor_from_page']) ? (int) $_SESSION['domstor_from_page'] : 1;
        return $page;
    }

    /**
     *
     * @return Domstor_Filter_DataLoaderConfig
     */
    public function getFilterDataLoaderConfig()
    {
        return $this->filter_data_loader_config;
    }
}
