@extends('layout.master')

@section('title', 'Perfil do Usuário')

@section('content')
<div class="d-flex justify-content-between align-items-center flex-wrap grid-margin">
    <div>
        <h4 class="mb-3 mb-md-0">Meu Perfil</h4>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="row">
    <!-- Informações do Perfil -->
    <div class="col-md-8 col-xl-9 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between mb-4">
                    <h6 class="card-title mb-0">Informações Pessoais</h6>
                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#editProfileModal">
                        <i data-lucide="edit-2" class="me-1"></i> Editar Perfil
                    </button>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold text-uppercase fs-11px">Nome Completo</label>
                            <p class="text-secondary mb-0">{{ $user->name }}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold text-uppercase fs-11px">E-mail</label>
                            <p class="text-secondary mb-0">{{ $user->email }}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold text-uppercase fs-11px">Data de Cadastro</label>
                            <p class="text-secondary mb-0">{{ $user->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold text-uppercase fs-11px">Última Atualização</label>
                            <p class="text-secondary mb-0">{{ $user->updated_at->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Card de Ações Rápidas -->
    <div class="col-md-4 col-xl-3 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h6 class="card-title mb-3">Ações Rápidas</h6>
                
                <div class="d-grid gap-2">
                    <button type="button" class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#changePasswordModal">
                        <i data-lucide="lock" class="me-1"></i> Alterar Senha
                    </button>
                    
                    <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary btn-sm">
                        <i data-lucide="home" class="me-1"></i> Voltar ao Dashboard
                    </a>
                </div>
                
                <hr class="my-3">
                
                <div class="text-center">
                    <div class="profile-photo-section mb-3">
                        <div class="position-relative d-inline-block">
                            @if($user->profile_photo)
                                <img src="{{ asset('storage/' . $user->profile_photo) }}" 
                                     alt="Foto de Perfil" 
                                     class="rounded-circle border border-3 border-light shadow" 
                                     style="width: 120px; height: 120px; object-fit: cover;" 
                                     id="profileImage">
                            @else
                                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center border border-3 border-light shadow" 
                                     style="width: 120px; height: 120px; font-size: 48px;" 
                                     id="profilePlaceholder">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                            @endif
                            
                            <!-- Botões de ação da foto -->
                            <div class="position-absolute bottom-0 end-0">
                                <div class="dropdown">
                                    <button class="btn btn-primary btn-sm rounded-circle" 
                                            type="button" 
                                            data-bs-toggle="dropdown" 
                                            aria-expanded="false"
                                            style="width: 36px; height: 36px;">
                                        <i data-lucide="camera" style="width: 16px; height: 16px;"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li>
                                            <a class="dropdown-item" href="#" onclick="document.getElementById('photoInput').click()">
                                                <i data-lucide="upload" class="me-2" style="width: 16px; height: 16px;"></i>
                                                {{ $user->profile_photo ? 'Alterar Foto' : 'Adicionar Foto' }}
                                            </a>
                                        </li>
                                        @if($user->profile_photo)
                                        <li>
                                            <form action="{{ route('profile.photo.remove') }}" method="POST" class="d-inline" id="removePhotoForm">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="dropdown-item text-danger" onclick="confirmRemovePhoto()">
                                                    <i data-lucide="trash-2" class="me-2" style="width: 16px; height: 16px;"></i>
                                                    Remover Foto
                                                </button>
                                            </form>
                                        </li>
                                        @endif
                                    </ul>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Input oculto para upload -->
                        <form action="{{ route('profile.photo.upload') }}" method="POST" enctype="multipart/form-data" id="photoForm" class="d-none">
                            @csrf
                            <input type="file" 
                                   id="photoInput" 
                                   name="profile_photo" 
                                   accept="image/jpeg,image/png,image/jpg,image/gif" 
                                   onchange="previewAndUpload(this)">
                        </form>
                    </div>
                    
                    <h4 class="mb-1 fw-bold">{{ $user->name }}</h4>
                    <p class="text-muted mb-0">{{ $user->email }}</p>
                    <small class="text-muted">Usuário do Sistema</small>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Editar Perfil -->
<div class="modal fade" id="editProfileModal" tabindex="-1" aria-labelledby="editProfileModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('profile.update') }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="modal-header">
                    <h5 class="modal-title" id="editProfileModalLabel">Editar Perfil</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="name" class="form-label">Nome Completo</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $user->name) }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="email" class="form-label">E-mail</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $user->email) }}" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Salvar Alterações</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Alterar Senha -->
<div class="modal fade" id="changePasswordModal" tabindex="-1" aria-labelledby="changePasswordModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('profile.password.update') }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="modal-header">
                    <h5 class="modal-title" id="changePasswordModalLabel">Alterar Senha</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="current_password" class="form-label">Senha Atual</label>
                        <input type="password" class="form-control @error('current_password', 'updatePassword') is-invalid @enderror" id="current_password" name="current_password" required>
                        @error('current_password', 'updatePassword')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="password" class="form-label">Nova Senha</label>
                        <input type="password" class="form-control @error('password', 'updatePassword') is-invalid @enderror" id="password" name="password" required>
                        @error('password', 'updatePassword')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">Confirmar Nova Senha</label>
                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                    </div>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Alterar Senha</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('plugin-scripts')
@endpush

@push('custom-scripts')
<script>
    // Reabrir modal em caso de erro
    @if($errors->any())
        @if($errors->updatePassword->any())
            var changePasswordModal = new bootstrap.Modal(document.getElementById('changePasswordModal'));
            changePasswordModal.show();
        @else
            var editProfileModal = new bootstrap.Modal(document.getElementById('editProfileModal'));
            editProfileModal.show();
        @endif
    @endif
    
    // Função para capitalizar primeira letra e letras após espaços
    function capitalizeNames(str) {
        return str.toLowerCase().replace(/\b\w/g, function(char) {
            return char.toUpperCase();
        });
    }

    // Aplicar formatação ao campo nome no modal de edição
    const nameInputProfile = document.getElementById('name');
    if (nameInputProfile) {
        nameInputProfile.addEventListener('input', function(e) {
            const cursorPosition = e.target.selectionStart;
            const formattedValue = capitalizeNames(e.target.value);
            e.target.value = formattedValue;
            
            // Manter posição do cursor
            e.target.setSelectionRange(cursorPosition, cursorPosition);
        });
    }

    // Função para preview e upload da foto
    function previewAndUpload(input) {
        if (input.files && input.files[0]) {
            const file = input.files[0];
            
            // Validar tipo de arquivo
            const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif'];
            if (!allowedTypes.includes(file.type)) {
                alert('Por favor, selecione apenas arquivos de imagem (JPEG, PNG, JPG, GIF).');
                return;
            }
            
            // Validar tamanho do arquivo (máximo 2MB)
            if (file.size > 2048 * 1024) {
                alert('O arquivo deve ter no máximo 2MB.');
                return;
            }
            
            // Criar preview
            const reader = new FileReader();
            reader.onload = function(e) {
                const profileImage = document.getElementById('profileImage');
                const profilePlaceholder = document.getElementById('profilePlaceholder');
                
                if (profileImage) {
                    profileImage.src = e.target.result;
                } else if (profilePlaceholder) {
                    // Substituir placeholder por imagem
                    profilePlaceholder.outerHTML = `
                        <img src="${e.target.result}" 
                             alt="Foto de Perfil" 
                             class="rounded-circle border border-3 border-light shadow" 
                             style="width: 120px; height: 120px; object-fit: cover;" 
                             id="profileImage">`;
                }
            };
            reader.readAsDataURL(file);
            
            // Fazer upload automaticamente
            document.getElementById('photoForm').submit();
        }
    }
    
    // Função para confirmar remoção da foto
    function confirmRemovePhoto() {
        if (confirm('Tem certeza que deseja remover sua foto de perfil?')) {
            document.getElementById('removePhotoForm').submit();
        }
    }
</script>
@endpush