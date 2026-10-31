<div class="row">
  <div class="col-lg-3 col-6">
      <div class="small-box bg-primary">
          <div class="inner">
              <p><h3 id="total-citas">0</h3>Citas</p>
          </div>
          <div class="icon">
              <i class="fas fa-calendar"></i>
          </div>
      </div>
  </div>
  <div class="col-lg-4 col-md-6 col-12">
      <div class="card card-outline card-info h-100">
          <div class="card-body d-flex flex-column justify-content-center">
              <h5 class="card-title mb-3">Exportar citas registradas</h5>
              <div class="d-flex flex-wrap">
                  <a href="{{ route('citas.exportar.pdf') }}" class="btn btn-danger mr-2 mb-2">
                      <i class="fas fa-file-pdf mr-1"></i> PDF
                  </a>
                  <a href="{{ route('citas.exportar.excel') }}" class="btn btn-success mb-2">
                      <i class="fas fa-file-excel mr-1"></i> Excel
                  </a>
              </div>
              <small class="text-muted">El archivo Excel se descarga en formato CSV compatible.</small>
          </div>
      </div>
  </div>
</div>