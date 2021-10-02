<div class="section-bg-1 before-bg-top bg-image-top pt-115 pb-70">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="section-title-area ltn__section-title-2 text-center">
                    <h1 class="section-title">Simulate Mortage</h1>
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
                                            <option selected></option>
                                            <option>Apartments</option>
                                            <option>Condos</option>
                                            <option>Duplexes</option>
                                            <option>Houses</option>
                                            <option>Industrial</option>
                                            <option>Land</option>
                                            <option>Offices</option>
                                            <option>Retail</option>
                                            <option>Villas</option>
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
                                        <input type="text" name="prices" placeholder="Property Prices" disabled>
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
                        <div class="btn-wrapper">
                            <button class="theme-btn-1 btn reverse-color btn-block" type="submit" id="simulate_btn">Simulate</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@push('scripts')
<script>
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
        alert('halo')
    });
</script>
@endpush