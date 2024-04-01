<div class="modal fade" id="formModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
aria-hidden="true">
<div class="modal-dialog" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Perubahan kriteria?</h5>
            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span>
            </button>
        </div>
        <form action="{{ route('kriteria.index') }}/{{ $kriteria->id }}" method="POST">
            @method('PUT')
            @csrf
            <div class="modal-body">
                <div class="form"></div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary" id="simpan_perubahan" name="simpan_perubahan">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>
</div>