<pre>
<?php
function copy_directory($src, $dst) {
  $dir = opendir($src);
  @mkdir($dst);
  while(false !== ( $file = readdir($dir)) ) {
    if (( $file != '.' ) && ( $file != '..' )) {
      if ( is_dir($src . '/' . $file) ) {
        copy_directory($src . '/' . $file, $dst . '/' . $file);
      } else {
        if (!file_exists($dst . '/' . $file)) {
          copy($src . '/' . $file, $dst . '/' . $file);
        }
      }
    }
  }
  closedir($dir);

  // Verificar se o arquivo config.json existe no diretório data e criá-lo se necessário
  if (is_dir($dst . '/data') && !file_exists($dst . '/data/config.json')) {
    file_put_contents($dst . '/data/config.json', '{}');
  }
}

$destinationPath = "new-folder";
$templatePath = "template";

// Criar a nova pasta
if (!file_exists($destinationPath)) {
  mkdir($destinationPath, 0777, true);
}

// Copiar os arquivos e as subpastas do template para a nova pasta
copy_directory($templatePath, $destinationPath);

// Obter as URLs do formulário
$playerSpriteUrl = isset($_POST['playerSpriteUrl']) ? stripslashes($_POST['playerSpriteUrl']) : 'https://raw.githubusercontent.com/TakeToBrasil/testeprimeiro/main/player.png';
$logoPngUrl = isset($_POST['logoPngUrl']) ? stripslashes($_POST['logoPngUrl']) : 'https://raw.githubusercontent.com/TakeToBrasil/testeprimeiro/main/logo.png';
$chaoUrl = isset($_POST['chaoUrl']) ? stripslashes($_POST['chaoUrl']) : 'https://raw.githubusercontent.com/TakeToBrasil/testeprimeiro/main/chao.png';
$fundoUrl = isset($_POST['fundoUrl']) ? stripslashes($_POST['fundoUrl']) : 'https://raw.githubusercontent.com/TakeToBrasil/testeprimeiro/main/fundo.png';

// Atualizar as URLs no arquivo config.json
$configJsonPath = $destinationPath . '/data/config.json';
$configJson = file_exists($configJsonPath) ? json_decode(file_get_contents($configJsonPath), true) : [];
$configJson['playerSpriteUrl'] = $playerSpriteUrl;
$configJson['logoPngUrl'] = $logoPngUrl;
$configJson['chaoUrl'] = $chaoUrl;
$configJson['fundoUrl'] = $fundoUrl;
file_put_contents($configJsonPath, json_encode($configJson, JSON_PRETTY_PRINT));

// Redirecionar para a página inicial
header("Location: index.html");
exit;
?>
</pre>