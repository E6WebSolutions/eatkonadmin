@extends("restaurants.layouts.restaurantslayout")

@section("restaurantcontant")


    <div class="container-fluid">
        <div class="card mb-4">
            <!-- Card header -->
            <div class="card-header">
                <h3 class="mb-0">{{$selected_language->data['store_category_editheading'] ?? 'Edit Category'}}</h3>
                @if(session()->has("MSG"))
                    <div class="alert alert-{{session()->get("TYPE")}}">
                        <strong> <a>{{session()->get("MSG")}}</a></strong>
                    </div>
                @endif
                @if($errors->any()) @include('admin.admin_layout.form_error') @endif
            </div>
            <!-- Card body -->
            <div class="card-body">
                <form method="post" action="{{route('store_admin.edit_category',['id'=>$data->id])}}" enctype="multipart/form-data">
                {{csrf_field()}}
                @method('PATCH')
                <!-- Form groups used in grid -->
                    <div class="row">

                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-control-label" for="example3cols1Input">{{$selected_language->data['store_category_image'] ?? 'Image'}}</label>




                                <div class="custom-file">
                                    <input name="image_url"  class="file-name input-flat ui-autocomplete-input" type="file" readonly="readonly" placeholder="Browses photo" autocomplete="off">


                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-control-label" for="example3cols2Input">{{$selected_language->data['store_category_name'] ?? 'Name'}}</label>
                                <input type="text"  name="name" value="{{$data->name}}" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-control-label" for="exampleFormControlSelect1">{{$selected_language->data['store_category_isenabled'] ?? 'Is Enabled'}}</label>
                                <select class="form-control" name="is_active" required>
                                    <option value="1" {{$data->is_active == 1 ? "selected":NULL}}>Enabled</option>
                                    <option value="0" {{$data->is_active == 0 ? "selected":NULL}}>Disabled</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <button class="btn btn-primary" type="submit">Submit</button>
                        </div>


                    </div>

                </form>
            </div>



        </div>


        <div class="card">
            <!-- Card header -->
            <div class="card-header border-0">
                <div class="row">
                    <div class="col-6">
                        <h3 class="mb-0"> Products (Total: {{count($products)}})


                        </h3>
                    </div>

                </div>
            </div>
            <!-- Light table -->
            <div class="table-responsive">
                <table class="table table-flush" id="datatable-basic">
                    <thead class="thead-light">
                    <tr>
                        <th>No</th>
                        <th>Image</th>
                        <th>Product</th>
                        <th>Price</th>
                        <th>Action</th>

                    </tr>
                    </thead>
                    <tbody>

                    @php $i=1 @endphp
                    @foreach($products as $product)
                        <tr>

                            <td>{{ $i++}}</td>
                            <td><img src="{{ asset($product->image_url !=NULL || $product->image_url!="" ? $product->image_url:'themes/default/images/all-img/empty.png')}}" style="width: 50px;height:50px"></td>

                            <td>{{$product->name}}</td>
                            <td>{{$product->price}}</td>

                            <td style="text-align: center">
                            <span>
                                <a class="btn btn-default btn-sm" href="{{route('store_admin.update_products',$product->id)}}">
                                    <i class="icofont-edit"></i>
                                </a>

                            </span>
                                <span class="deliv yes"><a class="btn btn-sm btn-danger" onclick="if(confirm('Are you sure you want to delete this item?')){ event.preventDefault();document.getElementById('delete-form-{{$product->id}}').submit(); }"><i class="icofont-bin"></i></a>
                                                        <form method="post" action="{{route('store_admin.delete_product')}}"
                                                              id="delete-form-{{$product->id}}" style="display: none">
                                                            @csrf
                                                            @method('DELETE')
                                                            <input type="hidden" value="{{$product->id}}" name="id">
                                                        </form>
                                </span>
                            </td>

                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div>
@endsection
