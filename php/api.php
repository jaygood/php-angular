
<?php
  class Product {
    private $dbserver = "localhost";
    private $dbuser = "atraders_jd";
    private $dbpass = "password242";
    private $dbdatabase = "atraders_angular_test";
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


























  $data = file_get_contents('php://input');
  $json = json_decode($data);
  $op = $json->{'op'};

  if(isset($op)){
    switch($op){

      case "getproducts":
        $obj = new Product();
        $ret = $obj->getProducts();
        $count = count( $ret, 1);
        $msg = $obj->getMsg();

        if(empty($msg)) {
          $resp = array('code' => -1, 'msg' => $msg);
        }else{ }

        $resp = array('code' => 1, 'msg' => '', 'data' => $ret);


        header('Content-Type: application/json');
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, POST');
        echo json_encode( $resp);
      break;

      case "save":
        $id = $json->{'data.id'};
        $name = $json->{'data'}->{'name'};
        $price = $json->{'data'}->{'price'};
        $obj = new Product();
        $code = -1;

        if( empty( $id) || $id ="") {
          // insert new product
          $code = $obj->insertProduct( $name, $price);
        }
        else{
          // update product
          $code = $obj->updateProduct( $id, $name, $price);
        }

        $resp = array('code' => $code, 'msg' => $obj->getMsg());
        header('Content-Type: application/json'); header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, POST');
        echo json_encode( $resp);
      break;

      case "delete":
        $id = $json->{'id'};
        $obj = new Product();
        $code = $obj->deleteProduct( $id);
        $resp = array('code' => $code, 'msg' => $obj->getMsg());

        header('Content-Type: application/json');
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, POST');
        echo json_encode( $resp);
      break;

      default:
        $ret = -999;
        $resp = array('code' => $ret, 'msg' => 'invalid operation');
        echo json_encode( $resp);
      break;
    }
  }
  else{
    $ret = -999;
    $resp = array('code' => $ret, 'msg' => 'invalid operationnnnn');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Origin: *');
    echo json_encode( $resp);
  }
?>
