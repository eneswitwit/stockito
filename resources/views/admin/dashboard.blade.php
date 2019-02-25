<section class="content">
    <div class="row">
        <div class="col-md-4">
            <div class="box box-primary">
                <div class="box-header">
                    <h3 class="box-title">Customers</h3>
                </div>
                <div class="box-body">
                    <ul class="todo-list">
                       <li><span class="text">Brands</span>
                           <small class="label label-danger">{{ $countBrand }}</small></li>
                        <li>
                            <span class="text">Creatives</span>
                            <small class="label label-danger">{{ $countCreative }}</small>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="box box-primary">
                <div class="box-header">
                    <h3 class="box-title">Subscriptions</h3>
                </div>
                <div class="box-body">
                    <ul class="todo-list">
                        @foreach($products as $product)
                            <li>
                                <span class="text">{{ $product->name }}</span>
                                <small class="label label-danger">{{ $product->getCountBrands() }}</small>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="box box-primary">
                <div class="box-header">
                    <h3 class="box-title">Used storage</h3>
                </div>
                <div class="box-body">
                    <ul class="todo-list">
                        @foreach($products as $product)
                            <li>
                                <span class="text">{{ $product->name }}</span>
                                <small class="label label-danger">{{ \App\Services\UploadService::getUnitsOfBytes($product->getUsedStorage()) }}</small>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>

        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header">
                    <h3 class="box-title">Uploads</h3>
                </div>
                <div class="box-body">
                    <ul class="todo-list">

                        @foreach($uploads as $activity)
                            <li><span class="text">{{ $activity->getText() }}</span>
                                <small class="label label-danger"><i class="fa fa-clock-o"></i> {{ $activity->updated_at }}</small>
                            </li>
                        @endforeach
                            <li class="text-center">
                                <a href="{{ route('admin.model', ['activities']) }}">(see all)</a>
                            </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header">
                    <h3 class="box-title">Shares</h3>
                </div>
                <div class="box-body">
                    <ul class="todo-list">
                        @foreach($shares as $share)
                            <li>
                                <span class="text">
                                    {{ implode(', ', $share->medias()->pluck('origin_name')->toArray()) }}
                                </span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header">
                    <h3 class="box-title">Logins</h3>
                </div>
                <div class="box-body">
                    <ul class="todo-list">
                        @foreach($loginsUser as $user)
                        <li><span class="text">{{ ($user->creative) ? "Creative": "Brand"}}</span> {{$user->getName()}}
                            <small class="label label-danger"><i class="fa fa-clock-o"></i> {{$user->last_login}}</small>
                        </li>
                        @endforeach
                        <li class="text-center">
                            <a href="{{ route('admin.model', ['logins']) }}">(see all)</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div id="export">
                <a href="{{ route('admin.dashboard.export') }}" class="btn btn-primary btn-block"> Export Newsletter List </a>
            </div>
        </div>
    </div>
</section>