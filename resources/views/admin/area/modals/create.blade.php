<!-- Create Area Modal -->
<div class="modal fade" id="createAreaModal" tabindex="-1" role="dialog" aria-labelledby="createAreaModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form class="modal-content" id="createAreaForm">
            <div class="modal-header">
                <h5 class="modal-title" id="createAreaModalLabel">Create Area</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="refCode">Reference Code
                        <span class="form-text text-muted d-inline" style="font-size: 0.8em;">
                            (Lowercase alphabets, numbers, dashes & underscores)</span>
                    </label>
                    </label>
                    <input type="text" class="form-control" name="ref" id="refCode"
                        placeholder="Enter reference number" value="{{ old('ref') }}" pattern="^[a-z0-9_\-]+$"
                        required>
                </div>
                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}"
                        placeholder="Enter name" required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Save changes</button>
            </div>
        </form>
    </div>
</div>
