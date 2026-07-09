<?php require_once "dependencias.php" ?>

<!-- Sidebar -->
<div class="sidebar" id="sidebar">
    <div class="sidebar-brand">
        <img src="../img/ximac.jpg" alt="EstoqueFlow">
        <h2>EstoqueFlow <small>Controle de Estoque</small></h2>
    </div>

    <nav class="sidebar-nav">
        <ul style="list-style:none; padding:0; margin:0;">
            <li class="nav-section">Principal</li>
            <li class="nav-item">
                <a href="inicio.php" class="nav-link" data-page="inicio">
                    <span class="nav-icon glyphicon glyphicon-dashboard"></span>
                    Painel Executivo
                </a>
            </li>

            <li class="nav-section">Produtos</li>
            <li class="nav-item">
                <a href="categorias.php" class="nav-link" data-page="categorias">
                    <span class="nav-icon glyphicon glyphicon-tags"></span>
                    Categorias
                </a>
            </li>
            <li class="nav-item">
                <a href="cadastroProdutos.php" class="nav-link" data-page="cadastroProdutos">
                    <span class="nav-icon glyphicon glyphicon-plus-sign"></span>
                    Cadastro de Produto
                </a>
            </li>
            <li class="nav-item">
                <a href="entradaEstoque.php" class="nav-link" data-page="entradaEstoque">
                    <span class="nav-icon glyphicon glyphicon-import"></span>
                    Entrada de Estoque
                </a>
            </li>
            <li class="nav-item">
                <a href="listaProdutos.php" class="nav-link" data-page="listaProdutos">
                    <span class="nav-icon glyphicon glyphicon-list"></span>
                    Lista de Produtos
                </a>
            </li>

            <li class="nav-section">Cadastros</li>
            <li class="nav-item">
                <a href="clientes.php" class="nav-link" data-page="clientes">
                    <span class="nav-icon glyphicon glyphicon-user"></span>
                    Clientes
                </a>
            </li>
            <li class="nav-item">
                <a href="fornecedores.php" class="nav-link" data-page="fornecedores">
                    <span class="nav-icon glyphicon glyphicon-briefcase"></span>
                    Fornecedores
                </a>
            </li>

            <li class="nav-section">Financeiro</li>
            <li class="nav-item">
                <a href="vendas.php" class="nav-link" data-page="vendas">
                    <span class="nav-icon glyphicon glyphicon-shopping-cart"></span>
                    Vendas
                </a>
            </li>

            <?php if($_SESSION['usuario'] == "admin"): ?>
            <li class="nav-section">Administração</li>
            <li class="nav-item">
                <a href="usuarios.php" class="nav-link" data-page="usuarios">
                    <span class="nav-icon glyphicon glyphicon-cog"></span>
                    Gestão de Usuários
                </a>
            </li>
            <?php endif; ?>
        </ul>
    </nav>

    <div class="sidebar-user">
        <div class="user-avatar">
            <?php echo strtoupper(substr($_SESSION['usuario'], 0, 1)); ?>
        </div>
        <div class="user-info">
            <div class="user-name"><?php echo $_SESSION['usuario']; ?></div>
            <div class="user-role"><?php echo ($_SESSION['usuario'] == 'admin') ? 'Administrador' : 'Usuário'; ?></div>
        </div>
        <a href="../procedimentos/sair.php" class="btn-logout" title="Sair do Sistema">
            <span class="glyphicon glyphicon-log-out"></span>
        </a>
    </div>
</div>

<!-- Overlay for mobile -->
<div class="sidebar-overlay" id="sidebarOverlay"></div>

<!-- Main Content Wrapper Start -->
<div class="main-content" id="mainContent">
    <!-- Top Bar -->
    <div class="topbar">
        <button class="btn-sidebar-toggle" id="sidebarToggle">
            <span class="glyphicon glyphicon-menu-hamburger"></span>
        </button>
        <div class="breadcrumb-area">
            <p class="page-title" id="topbarTitle"></p>
        </div>
        <div class="topbar-actions">
            <span class="text-muted-modern" style="font-size:0.8rem;">
                <?php echo date('d/m/Y'); ?>
            </span>
        </div>
    </div>

<script type="text/javascript">
$(document).ready(function(){
    var currentPage = window.location.pathname.split('/').pop().replace('.php','');
    $('.sidebar-nav .nav-link').each(function(){
        if($(this).data('page') === currentPage){
            $(this).addClass('active');
        }
    });

    var pageTitles = {
        'inicio': 'Dashboard',
        'categorias': 'Categorias',
        'cadastroProdutos': 'Cadastro de Produtos',
        'produtos': 'Produtos',
        'entradaEstoque': 'Entrada de Estoque',
        'listaProdutos': 'Lista de Produtos',
        'clientes': 'Clientes',
        'fornecedores': 'Fornecedores',
        'vendas': 'Vendas',
        'usuarios': 'Gestão de Usuários'
    };
    var title = pageTitles[currentPage] || 'EstoqueFlow';
    $('#topbarTitle').text(title);

    $('#sidebarToggle').click(function(){
        $('#sidebar').toggleClass('mobile-open');
        $('#sidebarOverlay').toggleClass('active');
    });

    $('#sidebarOverlay').click(function(){
        $('#sidebar').removeClass('mobile-open');
        $('#sidebarOverlay').removeClass('active');
    });
});
</script>
