<div class="section-bg-1 before-bg-top bg-image-top pt-115 pb-70">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="section-title-area ltn__section-title-2 text-center">
                    <h1 class="section-title">Simulate Mortgage</h1>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-6 offset-lg-3">
                <div class="account-login-inner">
                    <form class="ltn__form-box contact-form-box">
                        <div class="active">
                            <h6>Unit Type</h6>
                            <div class="row">
                                <div class="col-12">
                                    <div class="input-item">
                                        <select class="nice-select" id="unit_type">
                                            <option value="" selected></option>
                                            @if(isset($unit_type) && count($unit_type) > 0)
                                                @foreach($unit_type as $val)
                                                    <option data-id="{{$val->id}}" value="{{$val->name}}" data-price="{{$val->price}}">{{$val->name}}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="active">
                            <h6>Payment Method</h6>
                            <div class="row">
                                <div class="col-12">
                                    <div class="input-item">
                                        <select class="nice-select" id="payment_method">
                                            <option selected></option>
                                            <option value="cbt">CBT</option>
                                            <option value="kpr">KPR</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="hidden" id="dp_div">
                            <h6>Down Payment</h6>
                            <div class="row">
                                <div class="col-12">
                                    <div class="input-item">
                                        <select class="nice-select" id="down_payment">
                                            <option selected></option>
                                            <option value="5">5%</option>
                                            <option value="10">10%</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="active">
                            <h6>Property Prices (Estimate)</h6>
                            <div class="row">
                                <div class="col-12">
                                    <div class="input-item">
                                        <input type="text" name="prices" placeholder="Property Prices" disabled id="property_price">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="hidden" id="interest_div">
                            <h6>Interest Rate per Year (Estimate)</h6>
                            <div class="row">
                                <div class="col-12">
                                    <div class="input-item">
                                        <select class="nice-select" id="interest_rate">
                                            <option selected></option>
                                            <option value="11">11%</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="hidden" id="time_period_div">
                            <h6>Time Period</h6>
                            <div class="row">
                                <div class="col-12">
                                    <div class="input-item">
                                        <select class="nice-select" id="time_period">
                                            <option selected></option>
                                            <option value="5">5 Years</option>
                                            <option value="7">7 Years</option>
                                            <option value="10">10 Years</option>
                                            <option value="15">15 Years</option>
                                            <option value="20">20 Years</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <input type="text" id='uid' value="{{Request::get('uid')}}" hidden>
                        <div class="btn-wrapper">
                            <button class="theme-btn-1 btn reverse-color btn-block" type="submit" id="simulate_btn">Calculate</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@push('scripts')
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // format money
    var format = function(num){
        var str = num.toString().replace("", ""), parts = false, output = [], i = 1, formatted = null;
        if(str.indexOf(".") > 0) {
            parts = str.split(".");
            str = parts[0];
        }
        str = str.split("").reverse();
        for(var j = 0, len = str.length; j < len; j++) {
            if(str[j] != ".") {
            output.push(str[j]);
            if(i%3 == 0 && j < (len - 1)) {
                output.push(".");
            }
            i++;
            }
        }
        formatted = output.reverse().join("");
        return("" + formatted + ((parts) ? "." + parts[1].substr(0, 2) : ""));
    }

    $('#unit_type').change(function(e){
        e.preventDefault();
        var price = $(this).find(":selected").data('price');
        $('#property_price').attr('data-price',price);
        $('#property_price').val(format(price));
    });

    $('#payment_method').change(function(e){
        e.preventDefault();
        val = $(this).val();
        if(val == 'kpr'){
            $('#dp_div, #interest_div, #time_period_div').removeAttr('class');
            $('#dp_div, #interest_div, #time_period_div').attr('class', 'flex');
        } else {
            $('#dp_div, #interest_div, #time_period_div').removeAttr('class');
            $('#dp_div, #interest_div, #time_period_div').attr('class', 'hidden');
        }
    });
    
    $('#simulate_btn').click(function(e){
        e.preventDefault();
        var uid = $('#uid').val();
        var unit_type = $('#unit_type').find(":selected").data('id');
        var unit_type_name = $('#unit_type').find(":selected").val();
        var payment = $('#payment_method').find(":selected").val();
        // dp in percent
        var dp = $('#down_payment').find(":selected").val();
        var price = $('#property_price').data('price');
        // interest rate in percent
        var interest = $('#interest_rate').find(":selected").val();
        // time period in years
        var time_period = $('#time_period').find(":selected").val();
        // calculate
        if(!unit_type){
            Swal.fire({
                icon: 'warning',
                title: 'Pilih unit type terlebih dahulu',
                timer: 2000
            });
            return false;
        }
        if(payment){
            if(payment == 'cbt'){
                // calculate
                showModal(unit_type_name, payment, price, dp, interest, time_period);
            } else if(payment == 'kpr'){
                if(!dp){
                    Swal.fire({
                        icon: 'warning',
                        title: 'Pilih down payment terlebih dahulu',
                        timer: 2000
                    });
                    return false;
                }
                if(!interest){
                    Swal.fire({
                        icon: 'warning',
                        title: 'Pilih interest rate terlebih dahulu',
                        timer: 2000
                    });
                    return false;
                }
                if(!time_period){
                    Swal.fire({
                        icon: 'warning',
                        title: 'Pilih time period terlebih dahulu',
                        timer: 2000
                    });
                    return false;
                }
                // calculate
                showModal(unit_type_name, payment, price, dp, interest, time_period);
            }
        } else {
            Swal.fire({
                icon: 'warning',
                title: 'Pilih payment method terlebih dahulu',
                timer: 2000
            });
            return false;
        }
        // check if has user id then save.
        if(uid){
            $.ajax({
                url: "{{route('save.mortgage')}}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    uid: uid,
                    house_id: unit_type,
                    payment: payment,
                    time_period: time_period
                },
                success: function(resp){
                    console.log(resp);
                },
                error: function(err){
                    console.warn(err);
                }
            });
        }
    });

    var showModal = function(unit_type, payment, price, dp=null, interest=null, time_period=null){
        $('#modal_simulate').modal('show');
        var text = '';
        if(unit_type){
            text += 'You have selected <strong>'+unit_type+'</strong>.<br/>';
        }
        if(payment == 'cbt'){
            text += 'The amount you have to pay are <strong>IDR '+format(price)+'</strong>';
        } else {
            var res = calculateMortgage(price, dp, interest, time_period);
            text += res;
        }
        $('#text-result').html(text);
    }

    var calculateMortgage = function(price, dp, interest, time_period){
        var down_payment = price*(dp/100);
        var loan = price - down_payment;
        var interest_rate = interest/100;
        var monthly_interest = interest_rate/12;
        var period = time_period*12;
        // loan x ( interest rate ((1 + monthly interest)^number payment) ) / (((1+monthly interest)^number payment)-1)
        var x = 1 + monthly_interest;
        x = Math.pow(x, period);
        var y = x - 1;
        var z = interest_rate * x;
        z = z / y;
        var yearly = loan * z;
        var monthly = Math.ceil(yearly)/12;
        var res = Math.ceil(yearly) * time_period;
        var txt = 'with Down Payment of <strong>IDR '+format(down_payment)+'</strong><br/>';
        txt += 'Assumming an interest rate of '+interest+'%, then the amount you have to pay are<br/><strong>IDR '+format(Math.ceil(monthly))+'</strong> per month for <strong>'+time_period+'</strong> Years.'
        return txt;
    }
</script>
@endpush