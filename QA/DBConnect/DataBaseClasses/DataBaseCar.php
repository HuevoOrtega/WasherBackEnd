<?php
require_once dirname(__FILE__)."/DataBase.php";
class DataBaseCar {
  
  const QUERY_INSERT_CAR = "INSERT INTO Vehiculos_Favoritos (idVehiculo, idCliente, Favorito, Color, Placas, Marca)
		VALUES ('%s', '%s', 1, '%s', '%s', '%s' )
		;";
		const QUERY_DELETE_CAR = "DELETE FROM Vehiculos_Favoritos WHERE idVehiculoFavorito = '%s';";
		const QUERY_UPDATE_CAR = "UPDATE Vehiculos_Favoritos SET
		idVehiculo = '%s',
		Color = '%s',
		Placas = '%s',
		Marca = '%s'
		WHERE idVehiculoFavorito = '%s'
		;";
		const QUERY_READ_FAVORITE_CAR_LIST = "SELECT * FROM
		Vehiculos_Favoritos LEFT JOIN Vehiculo ON
		Vehiculos_Favoritos.idVehiculo = Vehiculo.idVehiculo
		WHERE idCliente = '%s'
		;";
		
		const QUERY_UNSET_FAV_CAR = "UPDATE Vehiculos_Favoritos SET Favorito = 0 WHERE idCliente = '%s'";
		const QUERY_SET_FAV_CAR = "UPDATE Vehiculos_Favoritos SET Favorito = 1 WHERE idVehiculoFavorito = '%s'";
    
    var $mysqli;
  
  public function __construct()
  {
		$this->mysqli = new mysqli(DataBase::DB_LINK,DataBase::DB_LOGIN,DataBase::DB_PASSWORD,DataBase::DB_NAME);
		if ($this->mysqli->connect_errno)
			throw new errorWithDatabaseException("Error connecting with database");
		$this->mysqli->set_charset("utf8");
  }
  
  public function insertCar($vehiculoId,$clientId,$color,$placas,$marca)
	{
		$query = sprintf(DataBaseCar::QUERY_INSERT_CAR,$vehiculoId,$clientId,$color,$placas,$marca);
		if(!($result = $this->mysqli->query($query)))
			throw new errorWithDatabaseException('Query failed'.$query);
		return $this->mysqli->insert_id;
	}
	
	public function deleteCar($favoriteCarId)
	{
		$query = sprintf(DataBaseCar::QUERY_DELETE_CAR,$favoriteCarId);
		if(!($result = $this->mysqli->query($query)))
			throw new errorWithDatabaseException('Query failed'.$query);
	}
	
	public function updateCar($vehiculoId,$vehiculoFavoritoId,$color,$placas,$marca){
		$query = sprintf(DataBaseCar::QUERY_UPDATE_CAR,$vehiculoId,$color,$placas,$marca,$vehiculoFavoritoId);
		if(!($result = $this->mysqli->query($query)))
			throw new errorWithDatabaseException('Query failed'.$query);
	}
	
	public function updateFavoriteCar($vehiculoFavoritoId,$clienteId){
		$query = sprintf(DataBaseCar::QUERY_UNSET_FAV_CAR,$clienteId);
		if(!($result = $this->mysqli->query($query)))
			throw new errorWithDatabaseException('Query failed'.$query);

		$query = sprintf(DataBaseCar::QUERY_SET_FAV_CAR,$vehiculoFavoritoId);
		if(!($result = $this->mysqli->query($query)))
			throw new errorWithDatabaseException('Query failed'.$this->mysqli->error);
	}
	
	public function readFavoriteCars($clientId)
	{
		$query = sprintf(DataBaseCar::QUERY_READ_FAVORITE_CAR_LIST,$clientId);
		if(!($result = $this->mysqli->query($query)))
			throw new errorWithDatabaseException('Query failed' .$this->mysqli->error);
		
    return $result;
	}
}