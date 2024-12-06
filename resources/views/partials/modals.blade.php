<!-- Create Category Modal -->
<div class="modal fade" id="createCategoryModal">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Create New Category</h5>
                <button class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <form action="" enctype="multipart/form-data" method="post" id="create-category-form">
                    @csrf
                    <div class="col-12 mb-10">
                        <label for="" class="form-label">Category Name</label>
                        <input type="text" class="form-control" name="category-name"
                            placeholder="Enter category name" required>
                    </div>


                    <div class="col-12">
                        <button type="submit" class="button button-primary">Create Category</button>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button class="button button-danger" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Create Status Modal -->
<div class="modal fade" id="createStatusModal">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Create New Status</h5>
                <button class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <form action="" enctype="multipart/form-data" method="post" id="create-status-form">
                    @csrf
                    <div class="col-12 mb-10">
                        <label for="" class="form-label">Status Name</label>
                        <input type="text" class="form-control" name="status-name" placeholder="Enter status name"
                            required>
                    </div>


                    <div class="col-12">
                        <button type="submit" class="button button-primary">Create status</button>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button class="button button-danger" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- List Categories Modal -->
<div class="modal fade" id="listCategoryModal">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">All Available Categories</h5>
                <button class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">

                <div class="box-body">
                    <ul class="list-group" id="category-list">

                    </ul>
                </div>

            </div>
            <div class="modal-footer">
                <button class="button button-danger" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- List Statuses Modal -->
<div class="modal fade" id="listStatusModal">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">All Available Statuses</h5>
                <button class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">

                <div class="box-body">
                    <ul class="list-group" id="status-list">

                    </ul>
                </div>

            </div>
            <div class="modal-footer">
                <button class="button button-danger" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Change Password Modal -->
<div class="modal fade" id="updatePasswordModal">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Update Your Password</h5>
                <button class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <form action="" enctype="multipart/form-data" method="post" id="update-password-form">

                    <div class="col-12 mb-10">
                        <label for="" class="form-label">Current Password:</label>
                        <input type="password" class="form-control" name="current-password"
                            placeholder="Enter your current password" required>
                    </div>

                    <div class="col-12 mb-10">
                        <label for="" class="form-label">New Password:</label>
                        <input type="password" class="form-control" name="new-password"
                            placeholder="Enter your new password" required>
                    </div>

                    <div class="col-12 mb-10">
                        <label for="" class="form-label">Confirm New Password:</label>
                        <input type="password" class="form-control" name="confirm-new-password"
                            placeholder="Confirm your new password" required>
                    </div>

                    <div class="col-12">
                        <button type="submit" class="button button-primary">Change Password</button>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button class="button button-danger" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="historyModal" tabindex="-1" role="dialog" aria-labelledby="historyModalLabel"
    aria-hidden="true">
    <div class="modal-dialog custom-modal-width" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-700 text-capitalize" id="historyModalLabel">Asset History</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="modalContent" class="table-responsive"></div>
            </div>
            <div class="modal-footer">
                <div id="paginationControls" class="d-flex justify-content-between align-items-center">
                    <button id="prevPage" class="btn btn-primary" disabled>Previous</button>
                    <span id="pageInfo"></span>
                    <button id="nextPage" class="btn btn-primary">Next</button>
                </div>

                {{-- <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button> --}}
            </div>
        </div>
    </div>
</div>
