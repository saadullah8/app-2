<!-- Modal -->
<div class="modal fade" id="editpromoModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Edit Promo Code</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form class="products" method="post" action="{{ route('promos.update') }}">
                <div class="modal-body">
                    <div class="col-12">
                        @csrf
                        <div class="row">
                            <div class="col-md-12 col-lg-12 col-sm-12">
                                <div class="form-group">
                                    <label>Promo Code</label>
                                    <input type="text" name="promoCode" id="EditedpromoCode" readonly class="form-control"
                                           required>
                                </div>
                                <input type="hidden" name="id" id="Editedid" class="form-control" readonly required>
                            </div>
                            <div class="col-md-12 col-lg-12 col-sm-12">
                                <div class="form-group">
                                    <label>Discount <small>(in percentage)</small></label>
                                    <input type="number" name="discountValue" onkeypress="return isNumberKey(event)"
                                           placeholder="Discount vale i.e. 5%,10%" id="EditeddiscountValue"
                                           class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label>Expiry Date</label>
                                    <input type="date" name="expiryDate"  id="EditedexpiryDates"
                                           class="form-control" required>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" id="EditedpromoSubmitter" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

