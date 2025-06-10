<div class="modal fade" id="personModal" tabindex="-1">
    <div class="modal-dialog">
        <form id="personForm">
            @csrf
            <input type="hidden" id="person_id">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 id="personModalLabel" class="modal-title">Add Person</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3"><label>Name</label><input type="text" class="form-control" name="name"
                            id="name" required></div>
                    <div class="mb-3"><label>Email</label><input type="email" class="form-control" name="email"
                            id="email" required></div>
                    <div class="mb-3">
                        <label for="role">Role</label>
                        <select name="role" id="role" class="form-control" required>
                            <option value="">Select Role</option>
                            <option value="Admin">Admin</option>
                            <option value="Designer">Designer</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="designation">Designation</label>
                        <input type="text" class="form-control" name="designation" id="designation" required>
                    </div>
                    <div class="mb-3">
                        <label for="photo" class="form-label">Photo</label>
                        <input type="file" class="form-control" id="photo" name="photo">
                        <div class="invalid-feedback" id="photoError"></div>
                    </div>


                    <div class="mb-3">
                        <label for="status">Status</label>
                        <select name="status" id="status" class="form-control" required>
                            <option value="Active">Active</option>
                            <option value="Inactive">Inactive</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label>Marital Status</label><br>
                        <label><input type="radio" name="marital_status" value="Married"> Married</label>
                        <label><input type="radio" name="marital_status" value="Unmarried"> Unmarried</label>
                    </div>
                    <div class="mb-3"><label>DOB</label><input type="date" class="form-control" name="dob"
                            id="dob" required></div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Save</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                </div>
            </div>
        </form>
    </div>
</div>
