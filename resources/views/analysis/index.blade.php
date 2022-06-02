<html>
    <head>
        <title>Berita</title>
    </head>

    <body>
        <table
            border="1"
            cellpadding="4"
            cellspacing="0"
            style="width:100%"
        >
            <thead>
                <tr>
                    <th>
                        No
                    </th>
                    <th>
                        Tanggal
                    </th>
                    <th>
                        Analisa
                    </th>
                    <th>
                        Jumlah Media
                    </th>
                </tr>
            </thead>
            <tbody>
                @foreach($data as $i => $value)
                
                <tr>
                    <td>
                        {{ $i + 1 }}
                    </td>
                    <td>
                        {{ $value->date }}
                    </td>
                    <td>
                        {!! $value->description !!}
                    </td>
                    <td>
                        {{ $value->qty }}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

    </body>
</html>
