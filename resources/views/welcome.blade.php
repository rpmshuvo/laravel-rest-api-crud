<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Item Mannager</title>
    <link rel="stylesheet" href="{{ asset('css/bootstrap.css') }}">
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <a class="navbar-brand" href="#">Item Manager</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarColor01" aria-controls="navbarColor01" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
      
        <div class="collapse navbar-collapse" id="navbarColor01">
          <ul class="navbar-nav mr-auto">
            <li class="nav-item ">
              <a class="nav-link" href="/">Home
              </a>
            </li>
          </ul>
        </div>
      </nav>

      <div class="container">
        <h1>Add item</h1>
        <form id="itemForm">
            <div class="form-group">
                <label for="">Text</label>
                <input type="text" name="text" id="text" class="form-control">
            </div>
            <div class="form-group">
                <label for="">Body</label>
                <textarea name="body" id="body" class="form-control"></textarea>
            </div>
            <input type="hidden" id="item_id" name="item_id" value="">
            <input type="submit" value="submit" class="btn btn-primary">
        </form>
        <br>
        <ul id="items" class="list-group">
        </ul>
      </div>
    
    <script src="https://code.jquery.com/jquery-1.12.4.js" integrity="sha256-Qw82+bXyGq6MydymqBxNPYTaUXXq7c8v3CwiYwLLNXU=" crossorigin="anonymous"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            getItems();

            //submit event
            $('#itemForm').on('submit', function (e) {
               e.preventDefault(); 
               let text = $('#text').val();
               let body = $('#body').val();

               addItem(text, body);
            });

            //Edit Event
            $('body').on('click', '.editLink', function (e) {
                e.preventDefault();
                let item_id = $(this).data('id');
                editItem(item_id);
            });

            //Edit item through api
            function editItem(id) {
                $.ajax({
                    method:'GET',
                    url:'http://itemapi.test/api/items/'+id,
                }).done(function (item) {
                    $('#text').val(item.text);
                    $('#body').val(item.body);
                    $('#item_id').val(item.id);
                    
                });
            }


            //Delete Event
            $('body').on('click', '.deleteLink', function (e) {
                e.preventDefault();
                let item_id = $(this).data('id');
                deleteItem(item_id);
            });


            //Delete item through api
            function deleteItem(id) {
                $.ajax({
                    method:'POST',
                    url:'http://itemapi.test/api/items/'+id,
                    data: {_method:'DELETE'}
                }).done(function (item) {
                    alert('Item Removed');
                    location.reload();
                });
            }

            //Insert item using API
            function addItem(text, body) {
                let item_id = $('#item_id').val();

                if (item_id != '') {
                    $.ajax({
                        method:'POST',
                        url:'http://itemapi.test/api/items/'+item_id,
                        data: {_method:'PUT', text:text, body:body}
                    }).done(function (item) {
                        alert('Item #'+ item.id +'Updated');
                        location.reload();
                    });
                }else{
                    $.ajax({
                        method:'POST',
                        url:'http://itemapi.test/api/items',
                        data: {text:text, body:body}
                    }).done(function (item) {
                        alert('Item #'+ item.id +'added');
                        location.reload();
                    });
                }

            }

            //Get items from API
            function getItems() {
                $.ajax({
                    url:'http://itemapi.test/api/items'
                }).done(function (items) {
                    let output = "";
                    $.each(items, function (key, item) {
                        output += `
                            <li class = "list-group-item">
                                <strong>${item.text}:</strong>${item.body} 
                                <a href="#" class="deleteLink" data-id="${item.id}">Delete</a>
                                <a href="#" class="editLink" data-id="${item.id}">Edit</a>
                            </li>
                        `;
                    });
                    $('#items').append(output);
                });
            }
        });
    </script>
</body>
</html>