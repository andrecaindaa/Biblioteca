<table>
    <thead>
        <tr>
            <th>Data</th>
            <th>User</th>
            <th>Módulo</th>
            <th>Ação</th>
            <th>Objeto</th>
            <th>IP</th>
            <th>Browser</th>
        </tr>
    </thead>
    <tbody>
        @foreach($logs as $log)
            <tr>
                <td>{{ $log->created_at }}</td>
                <td>{{ $log->user->name ?? 'Sistema' }}</td>
                <td>{{ $log->modulo }}</td>
                <td>{{ $log->acao }}</td>
                <td>{{ $log->objeto_id }}</td>
                <td>{{ $log->ip }}</td>
                <td>{{ Str::limit($log->browser, 40) }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
