<?php
class SP_Helper_Uri
{
	
	public function getQueryString()
	{
		return $_SERVER['QUERY_STRING'];
	}
	
	public function getRequestUri()
	{
		return $_SERVER['REQUEST_URI'];
	}
	
	public function replaceRequest($request=null)
	{
		if( !$request ) $recuest = self::getRequestUri();
	}
	
	public function replaceQueryString($replace=array())
	{
		$query = $_GET;


		foreach($query as $key => $value)
		{
			if( array_key_exists($key, $replace) )
			{
				if( $value==':NULL' )
				{
					unset( $query[$key] );
				}
				else
				{
					
					$query[$key]=$replace[$key];
				}
			}
			
		}

		$diff = array_diff_key($replace, $query);
		foreach($diff as $key => $value )
		{
			if( substr($key, -1)=='*' )
			{
				$find = substr($key, 0, -1);
				foreach($query as $key2 => $value2)
				{
					if( strpos($key2, $find)===0 )
					{
						if( $value==':NULL' )
						{
							unset( $query[$key2] );
						}
						else
						{
							
							$query[$key2]=$value;
						}
					}
				}
			}
			else
			{
				$query[$key]=$value;
			}
		}
		
		$out='';
		foreach($query as $key => $value )
		{
			$out.=$key.'='.$value.'&';
			
		}
		$out='?'.substr($out, 0, -1);
		return $out;
	}
	
}