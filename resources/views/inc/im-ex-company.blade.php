        <!-- Import and Export Systemlog Section -->
        <h3>Company Data</h3>
        <div class="well">
            <div class="row">
                <form action="{{url('company/import')}}" method="post" enctype="multipart/form-data">
                    {{csrf_field()}}
                    <div class="col-md-4">
                        <label for="imp_company" class="custom-file-upload">
                            <i class="fa fa-cloud-download"></i> Choose File
                        </label>
                        <input id="imp_company" type="file" name="imported-company"/>
                    </div>
                    <div class="col-md-4">
                        <button class="btn btn-primary btn-sm full-width" type="submit"><i class="fa fa-download" aria-hidden="true"></i> Import</button>
                    </div>
                </form>
                <div class="col-md-4">
                    <form action="{{url('company/export/excel')}}" enctype="multipart/form-data">
                        {{csrf_field()}}
                        <button class="btn btn-success btn-sm full-width" type="submit"><i class="fa fa-upload" aria-hidden="true"></i> Export As Excel File</button>
                    </form>
                </div>
            </div>
        </div>