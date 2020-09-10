<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">

    <title>Catalog Products</title>
  </head>
  <body>
    <div class="container">
 		<div class="row">
 			<div class="col-md-12">
 				<!-- Image and text -->
				<nav class="navbar navbar-light bg-light">
				  <a class="navbar-brand" href="{{ URL::to('admin') }}">
				    Syaanh
				  </a>
				</nav>
 			</div>
 			<div class="col-md-12">
 				<div class="alert alert-light" role="alert">
				  Category Products
				</div>
				<div class="alert alert-light" role="alert">
					<a href="{{ URL::to('sort/catalog') }}">Back</a>
				</div>
 			</div>
 		</div>
 		<div class="row">
 			<div class="col-md-12">
 				<div class="list-group">
 					<table class="table table-hover">
					  <thead>
					    <tr>
					      <th scope="col">#</th>
					      <th scope="col">Product Id</th>
					      <th scope="col">Category</th>
					      <th scope="col">Site</th>
					      <th scope="col">Product Name</th>
					      <th scope="col">Position</th>
					      {{-- <th scope="col">Pos</th> --}}
					    </tr>
					  </thead>
					  <tbody>
					  	<form action="{{ route('changePos') }}" method="POST">
						  	@foreach($prods as $key => $prod)
							    <tr>
								     <th scope="row">{{ $key }}</th>
								     <td>{{ $prod->refid }}</td>
								     <td>{{ App\Catalog::getCategoryNameFromId($prod->parentid) }}</td>
								     <td>{{ App\LocaleSite::getSiteNameFromId($prod->siteid) }}</td>
								     <td>{{ App\Product::getProdNameFromId($prod->refid) }}</td>
								     <td>
								      	<input class="form-control form-control-sm" name="pos[]" type="text" placeholder=".form-control-sm" value="{{ $prod->pos }}">
								      	<input type="hidden" name="prod[]" value="{{ $prod->refid }}">
								      	<input type="hidden" name="site[]" value="{{ $prod->siteid }}">
								      	<input type="hidden" name="category", value="{{ $prod->parentid }}">
								     </td>
								    {{--  <td><input class="form-control form-control-sm" name="position[]" type="text" placeholder=".form-control-sm" value="{{ $key }}"></td> --}}
							    </tr>
						    @endforeach
						    @if(count($prods) > 0)
						    	<input type="submit">
						    @endif
					    </form>
					  </tbody>
					</table>
 					{{-- @endforeach --}}
				</div>
 			</div>
 		</div>
	</div>
    

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
  </body>
</html>