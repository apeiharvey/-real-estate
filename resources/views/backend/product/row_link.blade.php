<div id="row-link-{{$i}}" class="link-row row mb-1">
    <div class="input-group">
        <select name="link_type[{{$i}}]" class="form-control">
            @if(isset($product->links[$i]->type))
                <option {{ $product->links[$i]->type == 'tokopedia' ? 'selected' : '' }} value="tokopedia">Tokopedia</option>
                <option {{ $product->links[$i]->type == 'shopee' ? 'selected' : '' }} value="shopee">Shopee</option>
            @else
                <option value="tokopedia">Tokopedia</option>
                <option value="shopee">Shopee</option>
            @endif
            
        </select>
        <input class="form-control" type="text" name="link_name[{{$i}}]" placeholder="name" value="{{isset($product->links[$i]->name) ? $product->links[$i]->name : '' }}" required>
        <input class="form-control" type="text" name="link_url[{{$i}}]" placeholder="url" value="{{isset($product->links[$i]->link) ? $product->links[$i]->link : '' }}" required>
        <button data-link-id="{{$i}}" type="button" class="btn btn-danger btn-del-link">Delete</button>
    </div>
</div>