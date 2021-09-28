@extends('includes.layout_admin')

@section('custom_title','Produk')

@section('admin_css')
    <link href="{{asset('assets/plugins/custom/lightbox/ekko-lightbox.css')}}" rel="stylesheet" type="text/css"/>
    <style>
        .input-group-text{
            padding: 0.5rem !important;
        }
        .card-scrollable-x {
            white-space: nowrap;
            overflow-x: auto;
            width: 100%;
        }
        .card-scrollable-x-item{
            width:450px;
            display:inline-block;
            vertical-align:top;
            margin: 5px 10px;
            padding: 0 0 0 20px;
        }
    </style>
@endsection

@section('pages')
<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
    <!--begin::Subheader-->
    <div class="subheader py-2 py-lg-6 subheader-solid" id="kt_subheader">
        <div class="container-fluid d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
            <!--begin::Info-->
            <div class="d-flex align-items-center flex-wrap mr-1">
                <!--begin::Page Heading-->
                <div class="d-flex align-items-baseline flex-wrap mr-5">
                    <!--begin::Breadcrumb-->
                    <ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
                        <li class="breadcrumb-item text-muted">
                            <a href="{{url()->current()}}" class="text-muted">@yield('custom_title')</a>
                        </li>
                        <li class="breadcrumb-item text-muted">
                            <a href="{{url()->current()}}" class="text-muted">Tambah Produk</a>
                        </li>
                    </ul>
                    <!--end::Breadcrumb-->
                </div>
                <!--end::Page Heading-->
            </div>
            <!--end::Info-->
            <!--begin::Toolbar-->
            <div class="d-flex align-items-center">
                <!--begin::Dropdown-->
                <div class="dropdown dropdown-inline">
                    <a href="#" class="btn btn-light-primary font-weight-bolder btn-sm" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Tambah Produk
                    </a>
                    <div class="dropdown-menu dropdown-menu-md dropdown-menu-right p-0 m-0">
                        <!--begin::Navigation-->
                        <ul class="navi navi-hover">
                            <li class="navi-header font-weight-bold py-4">
                                <span class="font-size-lg">Pilih opsi:</span>
                            </li>
                            <li class="navi-separator mb-3 opacity-70"></li>
                            <li class="navi-footer py-4">
                                <a class="btn btn-clean font-weight-bold btn-sm" href="{{route('product-form-add')}}">
                                    <i class="ki ki-plus icon-sm"></i>Tambah Melalui Form</a>
                            </li>
                            <li class="navi-footer py-4">
                                <a class="btn btn-clean font-weight-bold btn-sm" href="{{route('product-form-upload')}}">
                                    <i class="ki ki-long-arrow-up icon-sm"></i>Unggah Melalui File CSV</a>
                            </li>
                        </ul>
                        <!--end::Navigation-->
                    </div>
                </div>
                <!--end::Dropdown-->
            </div>
            <!--end::Toolbar-->
        </div>
    </div>
    <!--end::Subheader-->

    <!--begin::Entry-->
    <div class="d-flex flex-column-fluid">
        <!--begin::Container-->
        <div class="container">
{{--            {{ dump($selected) }}--}}
            <form id="addForm" onsubmit="return false;">
                <div class="d-flex flex-row">
                    <div class="flex-row-fluid ml-12">
                    
                        <div class="card card-custom">  <!-- card-custom -->
                            <!--begin::Header-->
                            <div class="card-header py-3">
                                <div class="card-title align-items-start flex-column">
                                    <h3 class="card-label font-weight-bolder text-dark">Informasi Produk</h3>
                                    <span class="text-muted font-weight-bold font-size-sm">Nama dan deskripsi produk</span>
                                </div>
                            </div>
                            <!--end::Header-->
                            <!--begin::Body-->
                            <div class="card-body pt-4">
                                <div class="d-flex p-2 justify-content-center">
                                    <div class="dropzone dropzone-default dropzone-success" id="dataListUpload">
                                        <div class="dropzone-msg dz-message needsclick">
                                            <h3 class="dropzone-msg-title">Taruh <b>gambar produk</b> disini atau <span class="text-success">cari</span></h3>
                                            <span class="dropzone-msg-desc">hanya .png/.jpeg/.jpg, menerima banyak file, dimensi pixel 800 x 600, ukuran hingga 3 MB</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-10">
                                    <div class="col-lg-6 col-xl-6">
                                        <div class="form-group">
                                            <label>Tipe</label>
                                            <select class="form-control select2 row product_type" id="kt_select2_item_type" name="item_type_id" data-pre="" required>
                                                <option label="Label"></option>
                                                @foreach($list_item_type as $item)
                                                    <option value="{{$item['value']}}">{{$item['name']}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group" id="name_wrap">
                                            <label>Nama</label>
                                            <input class="form-control form-control-lg form-control-solid" type="text" placeholder="" maxlength="225"
                                                name="name" required/>
                                        </div>
                                        <div class="form-group" id="name_to_search_wrap" style="display:none">
                                            <label>Nama Buku</label>
                                            <div class="input-group">
                                                <input class="form-control form-control form-control-solid" type="text" placeholder=""
                                                    name="name_to_search" onchange="getBookList()"/>
                                                <div class="input-group-append">
                                                    <button class="btn btn-warning btn-shadow" type="button" onclick="getBookList()">Cari</button>
                                                </div>
                                            </div>
                                            <input class="form-control form-control form-control-solid" type="text" name="puskurbuk_id" hidden/>
                                            <input class="form-control form-control form-control-solid" type="text" name="puskurbuk_isbn" hidden/>
                                            <input class="form-control form-control form-control-solid" type="text" name="puskurbuk_nuib" hidden/>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-xl-6">
                                        <div class="form-group">
                                            <label>Tingkat Pendidikan <small class="ml-3" style="color:#B5B5C3">(tidak wajib)</small></label>
                                            <select class="form-control select2" multiple="multiple" id="kt_select2_class" name="class_code[]">
                                                <option label="Label"></option>
                                                @foreach($list_class_level as $item)
                                                    <option value="{{$item['value']}}">{{$item['name']}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label>Kategori  <small class="ml-3" style="color:#B5B5C3">(silahkan pilih sampai anak kategori terkecil)</small></label>
                                            <select class="form-control select2 row select_category" id="kt_select2_category_1" onchange="changeCategory(1)"
                                                    name="category_id[]" data-level="1" data-allow_engender=1 required>
                                                <option label="Label"></option>
                                                @foreach($list_category as $item)
                                                    <option value="{{$item['id']}}">{{$item['category']}}</option>
                                                @endforeach
                                            </select>
                                            <div id="category_detail_more"></div>
                                        </div>
                                    </div>
                                </div>
                                <!-- <div class="row">   -->
                                    <div class="accordion accordion-solid accordion-toggle-plus mt-5" id="accordionBook" style="display:none">
                                        <div class="card">
                                            <div class="card-header"  id="kt_datatable_title">
                                                <div class="card-title collapsed bg-success text-white" data-toggle="collapse" data-target="#collapseBook">
                                                    Pilih buku dari hasil pencarian:
                                                </div>
                                            </div>
                                            <div id="collapseBook" class="collapse show" data-parent="#accordionBook">
                                                <div class="card-body text-start">
                                                    <div class="datatable datatable-bordered datatable-head-custom" id="kt_datatable"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <!-- </div> -->
                                <div class="row mt-10">                            
                                    <div class="col-lg-6 col-xl-6">
                                        <div class="form-group">
                                            <label>Brand</label>
                                            <input class="form-control form-control-lg form-control-solid" type="text" placeholder=""
                                                name="brand" maxlength="40" required/>
                                        </div>
                                        <div class="form-group">
                                            <label>Video Produk <small class="ml-3" style="color:#B5B5C3">(tidak wajib)</small></label>
                                            <input class="form-control form-control-lg form-control-solid" type="text" placeholder="contoh: https://www.youtube.com/watch?v=2gThxAqoUJU"
                                                name="video_link"  onkeypress="return event.charCode != 32"/>
                                            <div class="align-items-end"><br>
                                                <a style="display:none" id="preview-video" href="" data-toggle="lightbox" data-gallery="mixedgallery" class="btn btn-light-youtube btn-shadow font-weight-bold mr-2">
                                                    check preview video <i class="socicon-youtube ml-10"></i>
                                                </a>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label>Deskripsi</label>
                                            <textarea   class="form-control form-control-lg form-control-solid" placeholder=""
                                                        name="description" required rows="5"></textarea>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-xl-5 col-lg-5 col-form-label">Paket/Bundle</label>
                                            <div class="col-lg-7 col-xl-7">
                                                <div class="radio-inline mt-3">
                                                    <label class="radio radio-primary">
                                                        <input type="radio" name="is_packet" value=1 required>
                                                        <span></span>Ya
                                                    </label>
                                                    <label class="radio radio-primary">
                                                        <input type="radio" name="is_packet" value=0 required checked>
                                                        <span></span>Tidak
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group packet_description_wrap" style="display:none">
                                            <label>Deskripsi Paket</label>
                                            <textarea   class="form-control form-control-lg form-control-solid"
                                                        name="packet_description" rows="5"></textarea>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-xl-6">
                                    </div>
                                </div>
                            </div>
                            <!--end::Body-->
                        </div>
                        <br>
                        
                        
                        <div class="card card-custom">
                            <div class="card-header py-3">
                                <div class="card-title align-items-start flex-column">
                                    <h3 class="card-label font-weight-bolder text-dark">Harga</h3>
                                    <span class="text-muted font-weight-bold font-size-sm">Variasi harga untuk beragam <i>range</i> minimal pembelian</span>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row mb-10">
                                    <div class="col-lg-5">
                                        <div class="form-group">
                                            <label>Harga Dasar (listing price)</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">Rp.</span>
                                                </div>
                                                <input  class="form-control form-control-lg form-control-solid currency_mask" type="text" name="listing_price" required/>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <label class="checkbox checkbox-rounded">
                                    <input type="checkbox" name="is_every_zone_same_price" value=1>
                                    <span class="mr-5"></span>Jadikan harga di semua zona sama
                                </label>
                                <div class="card-scrollable-x mt-10">
                                    <?php
                                        $attribute_perzone = array(
                                            1 => array('subtitle2'=>'Jawa + Bali', 'subtitle'=>'Jawa Barat, Jawa Tengah, Jawa Timur, Daerah Istimewa Yogyakarta, DKI Jakarta, dan Banten'),
                                            2 => array('subtitle2'=>'Sumatera', 'subtitle'=>'Bali, Nusa Tenggara Barat, Lampung (kecuali Pesisir Barat), dan Sumatera Selatan'),
                                            3 => array('subtitle2'=>'Kalimantan + NTB + NTT', 'subtitle'=>'Bengkulu, Jambi, Bangka Belitung, Sumatera Barat (kecuali Kepulauan Mentawai dan Solok Selatan), Riau(kecuali Bengkalis, Kepulauan Meranti, , Sumatera Utara (kecuali Nias, Nias Selatan, Nias Utara, Nias Barat), Sulawesi Selatan, Sulawesi Utara(kecuali Kepulauan Sangihe, danKepulauan Talaud), Sulawesi Tengah(kecuali Banggai Kepulauan, Tojo UnoUno, Sigi, danBanggai Laut), Sulawesi Barat,Sulawesi Tenggara (kecualiKonawe, Bombana, danKonawe Kepulauan), dan Gorontalo'),
                                            4 => array('subtitle2'=>'Sulawesi', 'subtitle'=>'Nanggroe Aceh Darussalam (kecuali Aceh Besar dan Aceh Singkil), Kepulauan Riau (kecuali Karimun, Kepulauan Anambas, danNatuna), Nusa Tenggara Timur (kecuali Sumba Barat, Sumba Timur, Timor Tengah Selatan, Belu, Alor, Lembata, Ende, Manggarai, Rote Ndao, Manggarai Barat, Sumba Tengah, Sumba Barat Daya, , Nagekeo, Manggarai Timur, Sabu Raijua, dan Malaka), Kalimantan Barat (kecuali Kapuas Hulu, Sanggau), Kalimantan Selatan, Kalimantan Tengah, Kalimantan Timur (kecuali Mahakam Hulu, Berau), Kalimantan Timur, dan Kalimantan Utara (kecuali Nunukan, Malinau)'),
                                            5 => array('subtitle2'=>'Maluku + Papua', 'subtitle'=>'Papua, Papua Barat, Maluku, dan Maluku Utara. Aceh Besar, Aceh Singkil, Nias, Nias Selatan, Nias Utara, Nias Barat, Kep. Mentawai, Solok Selatan, Pesisir Barat, Sumba Barat, Sumba Timur, Timor Tengah Selatan, Belu, Alor, Lembata, Ende, Manggarai, Rote Ndao, Manggarai Barat, Sumba Tengah, Sumba Barat Daya, Nagekeo, Manggarai Timur, Sabu Raijua, Malaka, Banggai Kepulauan, Tujo Una-Una, Sigi, Banggai Laut, Konawe, Bombana, Konawe Kepulauan, Bengkalis, Kepulauan Meranti, Karimun, Kepulauan Anambas, Natuna, Kapuas Hulu, Mahakam Hulu, Sanggau, Nunukan, Malinau, Berau, Kepulauan Sangihe, dan Kepulauan Talaud')
                                        );

                                        $is_every_zone_same_price__affected_div_class = "is_every_zone_same_price__affected_div_class";
                                    ?>
                                    @for($i=1;$i<=5;$i++)
                                    <?php
                                        if($i>1){
                                            $is_every_zone_same_price__affected_div_class = "is_every_zone_same_price__affected_div_class";
                                            $is_every_zone_same_price__affected_input_class = "is_every_zone_same_price__affected_input_class";
                                        }else{
                                            $is_every_zone_same_price__affected_div_class = "";
                                            $is_every_zone_same_price__affected_input_class = "";
                                        }
                                    ?>
                                    <div class="bg-white rounded shadow-sm card-scrollable-x-item {{$is_every_zone_same_price__affected_div_class}}">
                                        <center>
                                            <span id="pricelist_zone{{$i}}_title" class="label label-warning label-pill label-inline font-size-h5">ZONA {{$i}}</span><br><br>
                                            <span class="text-muted mt-5 attribute_perzone_subtitle">{{$attribute_perzone[$i]['subtitle2']}}</span>
                                        </center><br>
                                        <div class="pt-10 pb-25 pb-md-10 px-1">
                                            <div class="form-group">
                                                <label>Harga Satuan</label>
                                                <div class="row">
                                                    <div class="col-lg-11">
                                                        <div class="input-group">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text">Rp.</span>
                                                            </div>
                                                            <input  class="form-control form-control-lg form-control-solid is_every_zone_same_price__affected_input_class currency_mask" 
                                                                    type="text" name="price[retail][{{$i}}][price]" required/>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <hr class="mt-10 mb-10">
                                            <div class="form-group mt-3">
                                                <label class="display_grosir_zone{{$i}}" style="display:none">Harga Grosir</label>
                                                <div class="row display_grosir_zone{{$i}}" style="display:none">
                                                    <div class="col-lg-5">
                                                        <label class="form-control-label" style="color:#B5B5C3">Minimal Pembelian</label>
                                                    </div>
                                                    <div class="col-lg-6">
                                                        <label class="form-control-label" style="color:#B5B5C3">Harga per satuan</label>
                                                    </div>
                                                    <div class="col-lg-1 mb-1">
                                                        <br>
                                                    </div>
                                                </div>
                                                <div id="pricelist_zone{{$i}}_more"></div>
                                                <div class="row">
                                                    <div class="col-lg-11">
                                                        <a  class="btn btn-block btn-sm btn-light-warning font-weight-bolder text-uppercase py-4 button-new-tier-price"  
                                                            onclick="addNewTierPrice({{$i}})"><i class="fas fa-plus fa-sm mr-3"></i> tambahkan harga grosir
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endfor
                                    <!--end: Pricing-Zone-->
                                </div>
                            </div>
                        </div>
                        <br>

                        <div class="row">
                            <div class="col-lg-6 col-xl-6">
                                <div class="card card-custom">
                                    <div class="card-header py-3">
                                        <div class="card-title align-items-start flex-column">
                                            <h3 class="card-label font-weight-bolder text-dark">Stok Awal</h3>
                                            <span class="text-muted font-weight-bold font-size-sm"></span>
                                        </div>
                                        {{--                            <div class="card-toolbar"></div>--}}
                                    </div>
                                    <div class="card-body">
                                        <div class="form-group row">
                                            <label class="col-xl-3 col-lg-3 col-form-label">Stok</label>
                                            <div class="col-lg-9 col-xl-9">
                                                <div class="row">
                                                    <div class="col-lg-5">
                                                        <label class="form-control-label" style="color:#B5B5C3">Jumlah</label>
                                                        <input  type="number" class="form-control form-control-lg form-control-solid" min="1"
                                                                name="stock" required>
                                                    </div>
                                                    <div class="col-lg-7">
                                                        <label class="form-control-label" style="color:#B5B5C3" title="Unit of Measurement">UoM</label>
                                                        <select class="form-control select2 row" id="kt_select2_uom" onchange="changeUoM()" 
                                                                name="sales_uom" required>
                                                            <option label="Label"></option>
                                                            @foreach($list_uom as $item)
                                                                <option value="{{$item['value']}}">{{$item['name']}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-xl-3 col-lg-3 col-form-label">SKU</label>
                                            <div class="col-lg-9 col-xl-9">
                                                <input  class="form-control form-control-lg form-control-solid" type="text" placeholder="" 
                                                        name="sku"  onkeypress="return event.charCode != 32" required/>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-xl-6">
                                <div class="card card-custom">
                                    <div class="card-header py-3">
                                        <div class="card-title align-items-start flex-column">
                                            <h3 class="card-label font-weight-bolder text-dark">Pengiriman</h3>
                                            <span class="text-muted font-weight-bold font-size-sm"></span>
                                        </div>
                                        {{--                            <div class="card-toolbar"></div>--}}
                                    </div>
                                    <div class="card-body">
                                        <div class="form-group row">
                                            <label class="col-xl-3 col-lg-3 col-form-label">Berat</label>
                                            <div class="col-lg-9 col-xl-9">
                                                <div class="row">
                                                    <div class="col-lg-9">
                                                        <label class="form-control-label" style="color:#B5B5C3">Berat Pengiriman</label>
                                                        <div class="input-group">
                                                            <input  type="number" class="form-control form-control-lg form-control-solid" min="0"
                                                                    name="gross_weight" required>
                                                            <div class="input-group-append">
                                                                <span class="input-group-text">kg</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-lg-9">
                                                        <label class="form-control-label" style="color:#B5B5C3">Berat Barang Bersih  <small class="ml-3" style="color:#B5B5C3">(tidak wajib)</small></label>
                                                        <div class="input-group">
                                                            <input  type="number" class="form-control form-control-lg form-control-solid" min="0"
                                                                    name="net_weight" step="any">
                                                            <div class="input-group-append">
                                                                <span class="input-group-text">kg</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-12">
                                                <label class="checkbox checkbox-rounded">
                                                    <input type="checkbox" name="is_free_ongkir">
                                                    <span class="mr-5"></span>Jadikan free ongkir
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <br>
                        
                        <div class="card card-custom">
                            <div class="card-header py-3">
                                <div class="card-title align-items-start flex-column">
                                    <h3 class="card-label font-weight-bolder text-dark">Atribut</h3>
                                    <span class="text-muted font-weight-bold font-size-sm"></span>
                                </div>
                                {{--                            <div class="card-toolbar"></div>--}}
                            </div>
                            <div class="card-body">
                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label">Dimensi</label>
                                    <div class="col-lg-9 col-xl-9">
                                        <div class="row">
                                            <div class="col-lg-4">
                                                <label class="form-control-label" style="color:#B5B5C3">Panjang</label>
                                                <div class="input-group">
                                                    <input  type="number" class="form-control form-control-lg form-control-solid" min="0"
                                                            name="dimension_length" required>
                                                    <div class="input-group-append">
                                                        <span class="input-group-text">cm</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-4">
                                                <label class="form-control-label" style="color:#B5B5C3">Lebar</label>
                                                <div class="input-group">
                                                    <input  type="number" class="form-control form-control-lg form-control-solid" min="0"
                                                            name="dimension_width" required>
                                                    <div class="input-group-append">
                                                        <span class="input-group-text">cm</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-4">
                                                <label class="form-control-label" style="color:#B5B5C3">Tinggi</label>
                                                <div class="input-group">
                                                    <input  type="number" class="form-control form-control-lg form-control-solid" min="0"
                                                            name="dimension_height" required>
                                                    <div class="input-group-append">
                                                        <span class="input-group-text">cm</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label">Kondisi Barang</label>
                                    <div class="col-lg-9 col-xl-9">
                                        <div class="radio-inline mt-3">
                                            @foreach($list_item_condition as $item)
                                                <label class="radio radio-primary">
                                                    <input type="radio" name="item_condition_type_id" value="{{$item['value']}}" required>
                                                    <span></span>{{$item['name']}}
                                                </label>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label">Produk UMKM</label>
                                    <div class="col-lg-9 col-xl-9">
                                        <span class="switch switch-sm switch-icon">
                                            <label>
                                                <input type="checkbox" class="product_origin" name="is_umkm" value=true><span></span>
                                            </label>
                                        </span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label">Produk Luar Negeri</label>
                                    <div class="col-lg-9 col-xl-9">
                                        <span class="switch switch-sm switch-icon">
                                            <label>
                                                <input type="checkbox" class="product_origin" name="made_in_indonesia" value=false><span></span>
                                            </label>
                                        </span>
                                    </div>
                                </div>
                                <!-- <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label">Penyedia adalah KEMENDIKBUD</label>
                                    <div class="col-lg-9 col-xl-9">
                                        <span class="switch switch-sm switch-icon">
                                            <label>
                                                <input type="checkbox" class="product_origin" name="is_kemendikbud" value=1><span></span>
                                            </label>
                                        </span>
                                    </div>
                                </div> -->
                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label">Waktu</label>
                                    <div class="col-lg-9 col-xl-9">
                                        <div class="row">
                                            <div class="col-lg-4">
                                                <label class="form-control-label" style="color:#B5B5C3">Lama Pre Order</label>
                                                <div class="input-group">
                                                    <input  type="number" class="form-control form-control-lg form-control-solid" min="0"
                                                            name="po_duration" required>
                                                    <div class="input-group-append">
                                                        <span class="input-group-text">hari</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-4">
                                                <label class="form-control-label" style="color:#B5B5C3">Lama Pengemasan Sampai Ke Kurir</label>
                                                <div class="input-group">
                                                    <input  type="number" class="form-control form-control-lg form-control-solid" min="0"
                                                            name="est_delivery" required>
                                                    <div class="input-group-append">
                                                        <span class="input-group-text">hari</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-4">
                                                <label class="form-control-label" style="color:#B5B5C3">Waktu Garansi</label>
                                                <div class="input-group">
                                                    <input  type="number" class="form-control form-control-lg form-control-solid" min="0"
                                                            name="warranty_days" required>
                                                    <div class="input-group-append">
                                                        <span class="input-group-text">hari</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label">Kode Supplier  <small class="ml-3" style="color:#B5B5C3">(tidak wajib)</small></label>
                                    <div class="col-lg- col-xl-9">
                                        <div class="row">
                                            <div class="col-lg-4">
                                                <input class="form-control form-control-lg form-control-solid" type="text" placeholder="" maxlength="10"
                                                    name="supplier_code"/>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!--begin::PUBLISH -->
                        <button type="button" class="btn btn-danger btn-lg btn-block button-add pb-5 pt-5">
                            <i class="fas fa-paper-plane fa-sm mr-3"></i>
                            <span id="button-add-info">Terbitkan</span>
                        </button>
                        <!--end::PUBLISH -->
                    </div>
                </div>
            </form>

        </div>
        <!--end::Container-->
    </div>
    <!--end::Entry-->
</div>
@endsection
@section('admin_js')
    <script src="{{ asset('assets/js/pages/crud/forms/widgets/select2.js') }}"></script>
    <script src="{{ asset('assets/plugins/custom/form/form.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/custom/lightbox/ekko-lightbox.min.js') }}"></script>
    <script src="{{ asset('assets/js/libraries/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('js/pages/product/form_add_update.js') }}?ver=00013"></script>
    <script src="{{ asset('js/pages/product/form_add.js') }}?ver=00011"></script>
@endsection

