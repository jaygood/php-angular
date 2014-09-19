class Product {
  private $dbserver = "localhost";
  private $dbuser = "username";
  private $dbpass = "password";
  private $dbdatabase = "mydatabase";
  private $msg = "";

  public function getMsg(){
    return $this->msg;
  }

  public function getProducts(){
    $product = array();
    try{
      $mysqli = new mysqli($this->dbserver, $this->dbuser,
                $this->dbpass, $this->dbdatabase);

      if ($mysqli->connect_errno){
        $this->msg = $mysqli->error;
        return$product;
      }

      $query = "select idproduct, name, price from product";

      if (!($stmt = $mysqli->prepare($query))) {
        $mysqli->close();
        $this->msg = $mysqli->error;
        return $product;
      }

      if (!$stmt->execute()) {
        $mysqli->close();
        $this->msg = $stmt->error;
        return $product;
      }
      else{
        $stmt->bind_result($id, $name, $price);

        while ($stmt->fetch()){
          $price_string = number_format((float) $price, 2, '.', '');
          array_push($product, array("id"=>$id,"name"=>$name,"price"=>$price_string));
        }
      }

      $stmt->close();
      $mysqli->close();
    }
    catch(Exception $e){
      $this->msg = $e->getMessage();
    }

    return $product;
  }

  public function insertProduct($name, $price){
    $product = -1;
    try{
      $mysqli = new mysqli($this->dbserver, $this->dbuser, $this->dbpass, $this->dbdatabase);

      if ($mysqli->connect_errno){
        $this->msg = $mysqli->error;
        return $product;
      }

      $query = "insert into product( name, price, created) values(?,?, now())";

      if (!($stmt = $mysqli->prepare( $query))) {
        $mysqli->close();
        $this->msg = $mysqli->error;
        return $product;
      }

      $newprice = floatval( $price);
      $stmt->bind_param('sd', $name, $newprice);

      if (! $stmt->execute()) {
        $mysqli->close();
        $this->msg = $stmt->error;
        return $product;
      }

      $product = 1;
      $this->msg = "";
      $stmt->close();
      $mysqli->close();
    }
    catch (Exception $e) {
      $this->msg = $e->getMessage();
    }

    return $product;
  }


  public function updateProduct( $id, $name, $price) {
    $product = -1;

    try {
      $mysqli = new mysqli( $this->dbserver, $this->dbuser, $this->dbpass, $this->dbdatabase);

      if ($mysqli->connect_errno) {
        $this->msg = $mysqli->error;
        return $product;
      }

      $query = "update product set name =?, price =? where idproduct =?";

      if (!($stmt = $mysqli->prepare( $query))) {
        $mysqli->close();
        $this->msg = $mysqli->error;
        return $product;
      }

      $newprice = floatval( $price);
      $stmt->bind_param('sdd', $name, $newprice, $id);

      if (! $stmt->execute()) {
        $mysqli->close();
        $this->msg = $stmt->error;
        return $product;
      }

      $product = 1;
      $this->msg = "";
      $stmt->close();
      $mysqli->close();
    }
    catch (Exception $e) {
      $this->msg = $e->getMessage();
    }
    return $product;
  }


  public function deleteProduct($id) {
    $product = -1;

    try {
      $mysqli = new mysqli( $this->dbserver, $this->dbuser, $this->dbpass, $this->dbdatabase);

      if ($mysqli->connect_errno) {
        $this->msg = $mysqli->error;
        return $product;
      }

      $query = "delete from product where idproduct =?";

      if (!( $stmt = $mysqli->prepare( $query))) {
        $mysqli->close();
        $this->msg = $mysqli->error;
        return $product;
      }

      $stmt->bind_param('d', $id);
      if (!$stmt->execute()) {
        $mysqli->close();
        $this->msg = $stmt->error;
        return $product;
      }

      $product = 1;
      $this->msg = "";
      $stmt->close();
      $mysqli->close();
    }
    catch (Exception $e) {
      $this->msg = $e->getMessage();
    }

    return $product;
  }
}
