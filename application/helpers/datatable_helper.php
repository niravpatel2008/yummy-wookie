<?php
class SSP {
	static function data_output ( $columns, $data )
	{
		$out = array();

		for ( $i=0, $ien=count($data) ; $i<$ien ; $i++ ) {
			$row = array();

			for ( $j=0, $jen=count($columns) ; $j<$jen ; $j++ ) {
				$column = $columns[$j];
				if(!isset($column['alias']))
				{
					$clmName = (isset( $column['coloumn_name'] ))?$column['coloumn_name']:$column['db'];
					$pos = strpos($clmName, ".");
					if ($pos !== false)
					{
						$clmName = substr($clmName, $pos+1);
					}
				}
				else 
					$clmName = $column['alias'];

				if ( isset( $column['formatter'] ) ) {
					$row[ $column['dt'] ] = $column['formatter']( $data[$i][ $clmName ], $data[$i] );
				}
				else {
						$row[ $column['dt'] ] = $data[$i][ $clmName ];
				}
			}
	
			$out[] = $row;
		}

		return $out;
	}

	/**
	 * Ordering
	 *
	 * Construct the ORDER BY clause for server-side processing SQL query
	 *
	 *  @param  array $request Data sent to server by DataTables
	 *  @param  array $columns Column information array
	 *  @return string SQL order by clause
	 */
	static function order ( $request, $columns )
	{
		$order = '';

		if ( isset($request['order']) && count($request['order']) ) {
			$orderBy = array();
			$dtColumns = SSP::pluck( $columns, 'dt' );

			for ( $i=0, $ien=count($request['order']) ; $i<$ien ; $i++ ) {
				// Convert the column index into the column data property
				$columnIdx = intval($request['order'][$i]['column']);
				$requestColumn = $request['columns'][$columnIdx];

				$columnIdx = array_search( $requestColumn['data'], $dtColumns );
				$column = $columns[ $columnIdx ];

				if ( $requestColumn['orderable'] == 'true' ) {
					$dir = $request['order'][$i]['dir'] === 'asc' ?
						'ASC' :
						'DESC';
					
					if(isset($column['sort']))
						$orderBy[] = $column['sort'].'||'.$dir;
					else if(isset($column['coloumn_name']))
						$orderBy[] = $column['coloumn_name'].'||'.$dir;
					else
						$orderBy[] = $column['db'].'||'.$dir;
				}
			}
		}

		return $orderBy;
	}

	/**
	 * Searching / Filtering
	 *
	 * Construct the WHERE clause for server-side processing SQL query.
	 *
	 * NOTE this does not match the built-in DataTables filtering which does it
	 * word by word on any field. It's possible to do here performance on large
	 * databases would be very poor
	 *
	 *  @param  array $request Data sent to server by DataTables
	 *  @param  array $columns Column information array
	 *  @return string SQL where clause
	 */
	static function filter ( $request, $columns)
	{
		$globalSearch = array();
		$columnSearch = array();
		$dtColumns = SSP::pluck( $columns, 'dt' );

		if ( isset($request['search']) && $request['search']['value'] != '' ) {
			$str = $request['search']['value'];

			for ( $i=0, $ien=count($request['columns']) ; $i<$ien ; $i++ ) {
				$requestColumn = $request['columns'][$i];
				$columnIdx = array_search( $requestColumn['data'], $dtColumns );
				$column = $columns[ $columnIdx ];

				if ( $requestColumn['searchable'] == 'true' ) {
					if(isset($column['coloumn_name']))
						$globalSearch[] = $column['coloumn_name']." LIKE ".'"%'.$str.'%"';
					else
						$globalSearch[] = $column['db']." LIKE ".'"%'.$str.'%"';
				}
			}
		}

		// Individual column filtering
		for ( $i=0, $ien=count($request['columns']) ; $i<$ien ; $i++ ) {
			$requestColumn = $request['columns'][$i];
			$columnIdx = array_search( $requestColumn['data'], $dtColumns );
			$column = $columns[ $columnIdx ];

			$str = $requestColumn['search']['value'];

			if ( $requestColumn['searchable'] == 'true' &&
			 $str != '' ) {
				if(isset($column['coloumn_name']))
					$columnSearch[] = $column['coloumn_name']." LIKE ".'"%'.$str.'%"';
				else
					$columnSearch[] = $column['db']." LIKE ".'"%'.$str.'%"';
			}
		}

		// Combine the filters into a single string
		$where = '';

		if ( count( $globalSearch ) ) {
			$where = '('.implode(' OR ', $globalSearch).')';
		}

		if ( count( $columnSearch ) ) {
			$where = $where === '' ?
				implode(' AND ', $columnSearch) :
				$where .' AND '. implode(' AND ', $columnSearch);
		}

		return $where;
	}


	/**
	 * Perform the SQL queries needed for an server-side processing requested,
	 * utilising the helper functions of this class, limit(), order() and
	 * filter() among others. The returned array is ready to be encoded as JSON
	 * in response to an SSP request, or can be modified if needed before
	 * sending back to the client.
	 *
	 *  @param  array $request Data sent to server by DataTables
	 *  @param  array $sql_details SQL connection details - see sql_connect()
	 *  @param  string $table SQL table to query
	 *  @param  string $primaryKey Primary key of the table
	 *  @param  array $columns Column information array
	 *  @return array          Server-side processing response array
	 */
	static function simple ( $request, $table, $primaryKey, $columns,$join = array(),$custom_where = array())
	{
		$bindings = array();
		$model = SSP::get_model();

		$fields = SSP::pluck_db($columns);
		$model->db->select("SQL_CALC_FOUND_ROWS ".$primaryKey, FALSE);
		$model->db->select($fields, FALSE);
		$model->db->from($table);

		foreach($join as $j)
		{
			$model->db->join($j[0], $j[1]);
		}
		
		$where = SSP::filter( $request, $columns, $bindings );
		if ($where != "") {
			$model->db->where($where);
		}	
		if ($custom_where != "") {
			$model->db->where($custom_where);
		}	
	
		foreach (SSP::order( $request, $columns ) as $order)
		{
			$order = explode("||",$order);
			$model->db->order_by($order[0],$order[1]);
		}

		$rows = isset($request['start'])?$request['start']:"";
		$limit = isset($request['length'])?$request['length']:0;
		$model->db->limit($limit, $rows);

		$query = $model->db->get();
		$cquery = $model->db->query('SELECT FOUND_ROWS() AS `Count`');

		$recordsFiltered = $cquery->row()->Count;
		$data = $query->result_array();
		//print_r($data);exit;
		$query->free_result();
		$model->db->select($primaryKey);
		$model->db->from($table);
		$query = $model->db->get();
		//print_r($query);exit;
		$recordsTotal = $query->num_rows();
		$query->free_result();
		

		return array(
			"draw"            => intval( $request['draw'] ),
			"recordsTotal"    => intval( $recordsTotal ),
			"recordsFiltered" => intval( $recordsFiltered ),
			"data"            => SSP::data_output( $columns, $data )
		);
	}


	/**
	 * Connect to the database
	 *
	 * @param  array $sql_details SQL server connection details array, with the
	 *   properties:
	 *     * host - host name
	 *     * db   - database name
	 *     * user - user name
	 *     * pass - user password
	 * @return resource Database connection handle
	 */
	static function get_model ()
	{
		$model = & get_instance();
        $model->load->model('common_model');
		return $model;
	}

	/**
	 * Pull a particular property from each assoc. array in a numeric array, 
	 * returning and array of the property values from each item.
	 *
	 *  @param  array  $a    Array to get data from
	 *  @param  string $prop Property to read
	 *  @return array        Array of property values
	 */
	static function pluck_db($a)
	{
		$out = array();

		for ( $i=0, $len=count($a) ; $i<$len ; $i++ ) {
			if (isset($a[$i]['alias']))
				$out[] = $a[$i]['db']." as ".$a[$i]['alias'];
			else
				$out[] = $a[$i]['db'];
		}
		return $out;
	}
	
	static function pluck ( $a, $prop )
	{
		$out = array();

		for ( $i=0, $len=count($a) ; $i<$len ; $i++ ) {
			$out[] = $a[$i][$prop];
		}

		return $out;
	}
}