<div class="ltn__modal-area ltn__quick-view-modal-area">
    <div class="modal fade" id="modal_form" tabindex="-1">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        <!-- <i class="fas fa-times"></i> -->
                    </button>
                </div>
                <div class="modal-body">
                    <div class="ltn__quick-view-modal-inner">
                        <div class="modal-product-item">
                            <div class="row">
                                <div class="col-12">
                                    <div class="modal-product-info">
                                        <div class="ltn__comment-reply-area ltn__form-box mb-30">
                                            <form action="{{route('submit.mortgage')}}" method="POST">
                                                @csrf
                                                <h4>Fill Out This Form to Help You Better</h4>
                                                <div class="mb-30"></div>
                                                <div class="input-item input-item-name ltn__custom-icon">
                                                    <input type="text" placeholder="Name" id="name" name="user_name" required>
                                                </div>
                                                <div class="input-item input-item-email ltn__custom-icon">
                                                    <input type="email" placeholder="Email" id="email" name="user_email" required>
                                                </div>
                                                <div class="input-item input-item-phone ltn__custom-icon">
                                                    <input type="text" name="user_phone" id="phone" placeholder="Phone Number" required>
                                                </div>
                                                <div class="btn-wrapper">
                                                    <button class="btn theme-btn-1 btn-effect-1 text-uppercase" type="submit">Simulate Mortgage</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@push('scripts')
<script>
     $("#phone").on("keypress keyup blur",function (event) {    
        $(this).val($(this).val().replace(/[^\d].+/, ""));
        if ((event.which < 48 || event.which > 57)) {
            event.preventDefault();
        }
    });
</script>
@endpush