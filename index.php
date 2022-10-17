<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Departamentos</title>
</head>

<body>
    <div>
        <?php
        $desde_codigo = (isset($_GET['desde_codigo'])) ? trim($_GET['desde_codigo']) : null;
        $hasta_codigo = (isset($_GET['hasta_codigo'])) ? trim($_GET['hasta_codigo']) : null;
        $denominacion = (isset($_GET['denominacion'])) ? trim($_GET['denominacion']) : null;


        $flag="";
        if($desde_codigo == ""){
            $flag.="0";
        } 
        else $flag.="1";
        if($hasta_codigo == ""){
            $flag.="0";
        } 
        else $flag.="1";
        if($denominacion == ""){
            $flag.="0";
        } 
        else $flag.="1";
        echo ($flag);
        ?>
        <div>
            <form action="" method="get">
                <fieldset>
                    <legend>Criterios de búsqueda</legend>
                    <p>
                        <label>
                            Desde código:
                            <input type="text" name="desde_codigo" size="8" value="<?= $desde_codigo ?>">
                        </label>
                    </p>
                    <p>
                        <label>
                            Hasta código:
                            <input type="text" name="hasta_codigo" size="8" value="<?= $hasta_codigo ?>">
                        </label>
                    </p>
                    <p>
                        <label>
                            Denominación:
                            <input type="text" name="denominacion"  value="<?= $denominacion ?> ">
                        </label>
                    </p>
                    <button type="submit">Buscar</button>

                </fieldset>
            </form>
        </div>
        <?php
        $pdo = new PDO('pgsql:host=localhost;dbname=empresa', 'empresa', 'empresa');

        $pdo->beginTransaction();
        $sent = $pdo->query('LOCK TABLE departamentos IN SHARE MODE');


        switch($flag):
            case "000"://todo vacío
                $sent = $pdo->prepare('SELECT COUNT(*)
                                         FROM departamentos');
                $sent->execute();
                break;
            case "110"://Desde hasta llenos
                
                break;
            case "100"://Lleno desde

                break;
            case "010"://Lleno hasta

                break;
            case "001"://Solo denominacion

                break;
            case "101"://Lleno Desde y denom

                break;
            case "011"://Lleno hasta y denom

                break;
            case "111"://Todo lleno

                break;
        endswitch;


        $sent = $pdo->prepare('SELECT COUNT(*)
                             FROM departamentos
                            WHERE codigo BETWEEN :desde_codigo AND :hasta_codigo AND denominacion LIKE :denominacion');
        $sent->execute([
            ':desde_codigo' => $desde_codigo,
            ':hasta_codigo' => $hasta_codigo,
            ':denominacion' => "%$denominacion%",
        ]);
        $total = $sent->fetchColumn();
        $sent = $pdo->prepare('SELECT *
                             FROM departamentos
                            WHERE codigo BETWEEN :desde_codigo AND :hasta_codigo AND denominacion LIKE :denominacion
                         ORDER BY codigo');
        $sent->execute([
            ':desde_codigo' => $desde_codigo,
            ':hasta_codigo' => $hasta_codigo,
            ':denominacion' => "%$denominacion%",
        ]);
        $pdo->commit();
        ?>
        <br>
        <div>
            <table style="margin: auto" border="1">
                <thead>
                    <th>Código</th>
                    <th>Denominación</th>
                </thead>
                <tbody>
                    <?php foreach ($sent as $fila) : ?>
                        <tr>
                            <td><?= $fila['codigo'] ?></td>
                            <td><?= $fila['denominacion'] ?></td>
                        </tr>
                    <?php endforeach ?>
                </tbody>
            </table>
            <p>Número total de filas: <?= $total ?></p>
        </div>
</body>

</html>