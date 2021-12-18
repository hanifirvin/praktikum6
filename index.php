<?php 
  if( !isset($_POST["submit"]) ) {
    $nama = "none";
    $kategori = "none";
    $durasi = 0;
    $tarif = 0;
    $abodemen = 0;
    $subTotal = 0;
    $pajak = 0;
    $total = 0;
  }
  
  // else if (isset($_POST["submit"]) && !isset($_POST["nama"]) ) {
  //   echo "
  //           <script>
  //               alert('fields nama_fields harus diisi');
  //           </script>
  //       ";
  // }

  else {
    $nama = $_POST["nama"];
    $kategori = $_POST["kategori"];
    $durasi = $_POST["durasi"];

    $arrayKategori = [
      [
          'kategori'  => 'Sosial',
          'abodemen' => 10000, 'tarif' => 300, 'pajak' => 0
      ] ,
      [
          'kategori'  => 'Rumah',
          'abodemen' => 30000, 'tarif' => 500, 'pajak' => 0.1
      ] ,
      [
          'kategori'  => 'Apartemen',
          'abodemen' => 50000, 'tarif' => 750, 'pajak' => 0.15
      ] ,
      [
          'kategori'  => 'Industri',
          'abodemen' => 75000, 'tarif' => 1000, 'pajak' => 0.2
      ] ,
      [
          'kategori'  => 'Villa',
          'abodemen' => 100000, 'tarif' => 1250, 'pajak' => 0.25
      ]
   ];

    class detailTagihan {
      public function calcTarif($kategori, $durasi, $arrayKategori) {
        foreach ($arrayKategori as $a) {
          if ($kategori == $a['kategori']) {
            $tarif = $a['tarif'];
            $totalTarif[0] = $tarif;

            for ($i = 0; $i<$durasi; $i++){
              $totalTarif[] = $totalTarif[$i] + $tarif;
            }
            
            return $totalTarif;
          }
        }
      }
  
      public function findAbodemen($kategori, $arrayKategori) {
        foreach ($arrayKategori as $a) {
            if ($kategori == $a['kategori']) {
              $abodemen = $a['abodemen'];
              return $abodemen;
            }
        }
      }

      public function findTarif($kategori, $arrayKategori) {
        foreach ($arrayKategori as $a) {
            if ($kategori == $a['kategori']) {
              $tarif = $a['tarif'];
              return $tarif;
            }
        }
      }

      public function calcSubTotal($abodemen, $tarif, $durasi) {
        $subTotal[0] = $abodemen + $tarif;

        for ($i = 0; $i<$durasi; $i++){
          $subTotal[] = $subTotal[$i] + $tarif;
        }
            
        return $subTotal;
        
      }
    
      public function pajak($kategori, $subTotal, $arrayKategori) {
        foreach ($arrayKategori as $a) {
          if ($kategori == $a['kategori']) {
            $pajak = $a['pajak'];

            $pajak = $pajak * $subTotal[count($subTotal)-2];
            return $pajak;
          }
        }

      }
      
      public function total($subTotal, $pajak) {
        // $last = $subTotal[count($subTotal)-1];
        $total = $subTotal[count($subTotal)-2] + $pajak;
        return $total;
      }
    }
  
    $client = new detailTagihan();
    $totalTarif = $client->calcTarif($kategori, $durasi, $arrayKategori);
    $abodemen = $client->findAbodemen($kategori, $arrayKategori);
    $tarif = $client->findTarif($kategori, $arrayKategori);
    $subTotal = $client->calcSubTotal($abodemen, $tarif, $durasi);
    $pajak = $client->pajak($kategori, $subTotal, $arrayKategori);
    $total = $client->total($subTotal, $pajak);
  }

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="style.css">
    <title>PT. Once Sejahtera</title>
  </head>
  <body>
    <div class="jumbotron">
      <img src="img/logo.png" alt="" />
      <h1>PT. ONCE SEJAHTERA</h1>
    </div>
    <div class="navigationBar">
      <button>Home</button>
      <button>Contact Us</button>
    </div>
    <div class="container">
      <div class="boxTransaksi">
        <h3>Data Transaksi</h3><br>

        <form action="<?= htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post" id='myForm'>
          <div>
            <label for="nama">Nama Pelanggan : </label>
            <input type="text" name="nama" id="nama" placeholder="Masukkan Nama" required>
          </div><br>

          <div>
            <label for="kategori">Kategori :</label>
            <select name="kategori" id="kategori" required>
              <option value="" disabled selected hidden>Kategori Peminjaman</option>
              <option value="Sosial">Sosial</option>
              <option value="Rumah">Rumah</option>
              <option value="Apartemen">Apartemen</option>
              <option value="Industri">Industri</option>
              <option value="Villa">Villa</option>
            </select>
          </div><br>

          <div>
            <label for="durasi">Pemakaian (hari) : </label>
            <input type="number" name="durasi" id="durasi" placeholder="Masukkan Durasi" required>
          </div><br>  
          
          <br><br>
          <button type="submit" name="submit" id="submit" onclick="getData()">Hitung</button>
        </form>
      </div>

      <div class="boxTagihan">
        <h3>TAGIHAN LISTRIK</h3>
        <table border="1" cellspacing="0" cellpadding="5"> 
          <tbody>
            <tr>
              <th scope="row">Nama Pelanggan</th>
              <td><?= $nama; ?></td>
            </tr>
            <tr>
              <th scope="row">Kategori</th>
              <td><?= $kategori; ?></td>
            </tr>  
            <tr>
              <th scope="row">Jumlah Pemakaian</th>
              <td><?= $durasi; ?></td>
            </tr>  
          </tbody>
     </div>

     <div class="boxRincian">
          <h4>Rincian Tagihan</h4>
          <table border="1" cellspacing="0" cellpadding="5">
          <thead>
            <th>Jumlah</th>
            <th>Tarif per KWH</th>
            <th>Abodemen</th>
            <th>SubTotal</th>
          </thead>
          <tbody>
            <?php if ($durasi == 0) : ?>
                <tr>
                 <th scope="row">0</th>
                 <td>0</td>
                 <td>0</td>
                 <td>0</td>
                </tr>
            <?php else : ?>
                <?php for ($num = 0; $num < $durasi; $num++) : ?>
                <tr>
                 <th scope="row"><?= $num+1; ?></th>
                 <td><?= $totalTarif[$num]; ?></td>
                 <td><?= $abodemen; ?></td>
                 <td><?= $subTotal[$num]; ?></td>
                </tr>
                <?php endfor; ?>
            <?php endif ?>
          </tbody>
        </table>

        <hr>
        <?php if ($durasi == 0) : ?>
         <p>Subtotal 0</p>
         <p>Pajak 0</p>
         <p>Bayar 0</p>
        <?php else : ?>
         <p>Subtotal <?= $subTotal[count($subTotal)-2]; ?></p>
         <p>Pajak <?= $pajak; ?></p>
         <p>Bayar <?= $total; ?></p>
        <?php endif ?>
      </div>

    </div>
  </body>

  <script>

    function getData(){
      var hari = document.getElementById("myForm").elements[2].value;
      hari = parseInt(hari);
            
        if((hari<1)||(hari>30)){
          return alert('Jumlah hari tidak valid');
        }
    }

    document.getElementById("nama").oninvalid = function() {namaFunction()};
    document.getElementById("kategori").oninvalid = function() {kategoriFunction()};
    document.getElementById("durasi").oninvalid = function() {durasiFunction()};

    function namaFunction() {
      alert("fields nama_fields harus diisi!");
    }

    function kategoriFunction() {
      alert("fields kategori_fields harus diisi!");
    }

    function durasiFunction() {
      alert("fields durasi_fields harus diisi!");
    }
  </script>
</html>
