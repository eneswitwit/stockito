<table class="table">
    <tr>
        <th>Total Storage</th>
        <th>{{ $sData['allFormated'] }}</th>
    </tr>
    <tr>
        <th>Used Storage</th>
        <th>{{ $sData['usedFormated'] }}</th>
    </tr>
    <tr>
        <th>Last Login</th>
        <th>{{ $model->user->last_login }}</th>
    </tr>
</table>