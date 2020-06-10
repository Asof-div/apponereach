<div class="modal fade" id="suspend_customer_modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('operator.customer.suspend', [$customer->id]) }}" method="POST" id="suspend_customer_form">
                {{ csrf_field() }}

                <input type="hidden" name="customer_id" value="{{ $customer->id }}">
                <input type="hidden" name="status" value="">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title"> Suspend Customer Account </h4>
                </div>
                <div class="modal-body">
                    <div class="f-s-15">
                        Kindly, confirm you want to suspend this customer account. And all services will be deactivated on confirmation.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success"> <i class="fa fa-exchange"></i> Suspend </button>
                </div>

            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="renew_customer_modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('operator.customer.renew', [$customer->id]) }}" method="POST" id="renew_customer_form">
                {{ csrf_field() }}

                <input type="hidden" name="customer_id" value="{{ $customer->id }}">
                <input type="hidden" name="status" value="">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title"> Renew Customer Account </h4>
                </div>
                <div class="modal-body">
                    <div class="f-s-15">
                        Kindly, confirm you want to renew this customer account. And all services will be activated on confirmation.
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success"> <i class="fa fa-exchange"></i> Activate </button>
                </div>

            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="delete_customer_modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('operator.customer.delete', [$customer->id]) }}" method="POST" id="delete_customer_form">
                {{ csrf_field() }}

                <input type="hidden" name="customer_id" value="{{ $customer->id }}">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title"> DELETE CUSTOMER ACCOUNT </h4>
                </div>
                <div class="modal-body">
                    <div class="f-s-15">
                        Kindly, confirm you want to delete this customer account. And all infomation about this customer will be permanently leave the database.
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-danger"> <i class="fa fa-trash"></i> DELETE CUSTOMER</button>
                </div>

            </form>
        </div>
    </div>
</div>


<div class="modal fade" id="revive_customer_modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('operator.customer.revive', [$customer->id]) }}" method="POST">
                {{ csrf_field() }}

                <input type="hidden" name="customer_id" value="{{ $customer->id }}">
                <input type="hidden" name="status" value="">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title"> Suspend Customer Account </h4>
                </div>
                <div class="modal-body">
                    <div class="f-s-15">
                        Kindly, confirm you want to suspend this customer account. And all services will be deactivated on confirmation.
                    </div>
                    <div class="form-group m-t-20 f-s-15">
                        <label><input type="radio" name="effect" value="now" checked="checked"> Immediate Effect </label>
                        <label><input type="radio" name="effect" value="month_end"> End Of Current Subscription </label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success"> <i class="fa fa-exchange"></i> Suspend </button>
                </div>

            </form>
        </div>
    </div>
</div>