@if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show mb-2" role="alert">
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        Se encontraron los errores:
        <ul>
            @foreach($errors->all() as $error)
                <li class="col-xs-12">{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@elseif(session()->has('mensaje-estatus'))
    <div class="alert alert-{{ session()->get('mensaje-estatus')['css'] }} alert-dismissible fade show mb-2" role="alert">
        
        {{ session()->get('mensaje-estatus')['mensaje'] }}
    </div>
                    @elseif(session()->has('success'))

                        <div class="desaparecer alert alert-success alert-dismissible fade show rounded-0 mb-2">
                            <div class="d-flex">
                                <i class="fa fa-check fa-2x me-1"></i>
                                <div class="mb-0 ps-2">
                                    {{ session()->get('success') }}
                                </div>
                            </div>
                            <button type="button" class="btn-close ms-3" data-bs-dismiss="alert"></button>
                        </div>
                    
                    @endif