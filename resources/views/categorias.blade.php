<!DOCTYPE html>
<html
    lang="es"
>           
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">  
    <title>Categoriasssss</title>
</head>
<body>

<table border="1" cellpadding="8" cellspacing="0" style="border-collapse: collapse; width: 100%;">
    <thead>
        <tr style="background-color: #f2f2f2;"> 
            <th>ID</th>
            <th>Nombrew</th>
        </tr>
    </thead>
    <tbody>
        @foreach($categorias as $categoria)
            <tr>
                <td>{{ $categoria->id }}</td>
                <td>{{ $categoria->nombre }}</td>
                
            </tr>
        @endforeach
    </tbody>
</table>
</body>
</html>