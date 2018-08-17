<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header">
                    <h3 class="box-title">Activity</h3>
                </div>
                <div class="box-body">
                    <ul class="todo-list">
                        <li><span class="text">Last Login</span>
                            <small class="label label-danger"><i class="fa fa-clock-o"></i>{{$creative->user->last_login}}</small>
                        </li>

                        @foreach($creative->activities() as $activity)
                            <li><span class="text">{{$activity}}</span>
                                <small class="label label-danger"><i class="fa fa-clock-o"></i> 2018-06-08 11:49:10</small>
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
                    <h3 class="box-title">Creative Details</h3>
                </div>
                <div class="box-body">
                    <form action="{{ route('admin.creative.edit', ['id'=>$creative->id]) }}"  method="post" class="form-horizontal">
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Firstname</label>
                            <div class="col-sm-10">
                                <input type="text" name="first_name"  value="{{$creative->first_name}}" class="form-control" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputBrandname" class="col-sm-2 control-label">Lastname</label>
                            <div class="col-sm-10">
                                <input type="text" name="last_name"  value="{{$creative->last_name}}" class="form-control" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputBrandname" class="col-sm-2 control-label">Email</label>
                            <div class="col-sm-10">
                                <input type="email"  name="email" value="{{$creative->user->email}}" class="form-control" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputBrandname" class="col-sm-2 control-label"> Company/Agency</label>
                            <div class="col-sm-10">
                                <input type="text" name="company" value="{{$creative->company}}" class="form-control" required>
                            </div>
                        </div>
                        <div class="box-footer">
                            <button type="submit" class="btn btn-primary">Edit</button>

                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header">
                    <h3 class="box-title">Brands Involved</h3>
                </div>
                <div class="box-body">
                    <ul class="todo-list">
                        @foreach($creative->brands as $brand)
                            <li><span class="text">{{$brand->name}}</span>
                                <div class="pull-right">
                                    <a href="{{ route('admin.brand.detail', ['id'=>$brand->id]) }}"><i class="fa fa-eye"></i></a>
                                    <a href="{{ route('admin.creative.remove_brand', [
                                        'brand_id' => $brand->id,
                                         'id' =>$creative->id]) }}">
                                        <i class="fa fa-trash-o"></i></a>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>

    </div>



</section>
