<html>
    <head>
        <title>Berita</title>
    </head>

    <body>
        <table
            border="1"
            cellpadding="4"
            cellspacing="0"
        >
            <thead>
                <tr>
                    <th>
                        No
                    </th>
                    <th>
                        Judul
                    </th>
                    <th>
                        Konten
                    </th>
                    <th>
                        Tanggal
                    </th>
                    <th>
                        Sumber Berita
                    </th>
                    <th>
                        Sentimen
                    </th>
                    <th>
                        Sentimen Positif
                    </th>
                    <th>
                        Sentimen Negatif
                    </th>
                    <th>
                        Sentimen Netral
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
                        {{ $value->title }}
                    </td>
                    <td>
                        {!! $value->textcontent !!}
                    </td>
                    <td>
                        {{ $value->published_date }}
                    </td>
                    <td>
                        {{ $value->source }}
                    </td>
                    <td>
                        {{ $value->sentiment }}
                    </td>
                    <td>
                        {{ $value->sentpos }}
                    </td>
                    <td>
                        {{ $value->sentneg }}
                    </td>
                    <td>
                        {{ $value->sentneutral }}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

    </body>
</html>
