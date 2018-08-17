@php($user = $brand->user)
@php($subscription = $user->subscription('main'))
<table class="table">
    @if($product)
        <tr>
            <th>Product</th>
            <td><a href="{{ route('admin.model.edit', ['products', $product->id]) }}">{{ $product->name }}</a></td>
        </tr>
        <tr>
            <th>Storage</th>
            <td>{{ \App\Services\UploadService::getUnitsOfBytes($product->storage) }}</td>
        </tr>
    @endif
    @if($subscription)
        <tr>
            <th>Trial period</th>
            <td>{{ $subscription->onTrial() ? 'Yes' : 'No' }}</td>
        </tr>
        <tr>
            <th>Ends at</th>
            <td>
                @if($subscription->ends_at)
                    {{ $subscription->ends_at }}
                @else
                    Auto renewal
                @endif
            </td>
        </tr>
    @endif
</table>