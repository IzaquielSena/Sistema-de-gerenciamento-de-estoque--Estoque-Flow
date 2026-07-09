<?php
    require_once "../../classes/conexao.php";
    require_once "../../classes/dashboard.php";
    require_once "../../classes/vendas.php";

    $obj  = new dashboard();
    $objV = new vendas();

    $vendasHoje    = $obj->totalVendasHoje();
    $vendasMes     = $obj->totalVendasMes();
    $qtdVendasMes  = $obj->quantidadeVendasMes();
    $ticketMedio   = $qtdVendasMes > 0 ? $vendasMes / $qtdVendasMes : 0;
    $baixoEstoque  = $obj->totalProdutosBaixoEstoque();
    $totalEntradas = $obj->totalEntradas();
    $totalSaidas   = $obj->totalSaidas();
    $lucroTotal    = $obj->lucroTotal();
    $margemLucro   = $totalSaidas > 0 ? ($lucroTotal / $totalSaidas) * 100 : 0;

    // Top produtos para barra de progresso
    $topProdResult = $obj->topProdutosMaisVendidos(5);
    $topProdutos = [];
    $maxVendido = 1;
    while($r = mysqli_fetch_assoc($topProdResult)) {
        $topProdutos[] = $r;
        if ($r['total_vendido'] > $maxVendido) $maxVendido = $r['total_vendido'];
    }

    // Faturamento por categoria para barra de progresso
    $fatCatResult = $obj->faturamentoPorCategoria();
    $fatCategorias = [];
    $maxFat = 1;
    while($r = mysqli_fetch_assoc($fatCatResult)) {
        $fatCategorias[] = $r;
        if ($r['faturamento'] > $maxFat) $maxFat = $r['faturamento'];
    }

    // Produtos com baixo estoque detalhado
    $prodBaixoResult = $obj->produtosBaixoEstoque(20);
    $prodBaixo = [];
    while($r = mysqli_fetch_assoc($prodBaixoResult)) {
        $prodBaixo[] = $r;
    }

    // Últimas vendas
    $ultVendasResult = $obj->ultimasVendas(6);
    $ultVendas = [];
    while($r = mysqli_fetch_assoc($ultVendasResult)) {
        $ultVendas[] = $r;
    }

    $mesNome = ['', 'Janeiro','Fevereiro','Março','Abril','Maio','Junho',
                'Julho','Agosto','Setembro','Outubro','Novembro','Dezembro'];
    $mesAtual = $mesNome[(int)date('m')];
?>

<style>
/* ── Dashboard Layout ───────────────────────────────────── */
.dash-wrap { padding: 0 4px; }

/* ── KPI Cards ──────────────────────────────────────────── */
.kpi-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 16px;
    margin-bottom: 24px;
}
.kpi-card {
    background: var(--white);
    border-radius: var(--border-radius-lg);
    box-shadow: var(--shadow-md);
    padding: 20px 22px;
    display: flex;
    flex-direction: column;
    gap: 10px;
    border-top: 4px solid transparent;
    transition: var(--transition);
    position: relative;
    overflow: hidden;
}
.kpi-card::after {
    content: '';
    position: absolute;
    right: -14px;
    top: -14px;
    width: 70px;
    height: 70px;
    border-radius: 50%;
    opacity: 0.08;
}
.kpi-card:hover { transform: translateY(-2px); box-shadow: var(--shadow-lg); }
.kpi-card.blue  { border-color: var(--primary); }
.kpi-card.green { border-color: var(--success); }
.kpi-card.amber { border-color: var(--warning); }
.kpi-card.red   { border-color: var(--danger);  }
.kpi-card.teal  { border-color: var(--info);    }
.kpi-card.blue::after  { background: var(--primary); }
.kpi-card.green::after { background: var(--success); }
.kpi-card.amber::after { background: var(--warning); }
.kpi-card.red::after   { background: var(--danger);  }
.kpi-card.teal::after  { background: var(--info);    }

.kpi-top { display: flex; align-items: center; justify-content: space-between; }
.kpi-icon {
    width: 42px; height: 42px;
    border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    font-size: 18px;
}
.kpi-card.blue  .kpi-icon { background: var(--primary-bg);   color: var(--primary); }
.kpi-card.green .kpi-icon { background: var(--success-light); color: var(--success); }
.kpi-card.amber .kpi-icon { background: var(--warning-light); color: var(--warning); }
.kpi-card.red   .kpi-icon { background: var(--danger-light);  color: var(--danger);  }
.kpi-card.teal  .kpi-icon { background: var(--info-light);    color: var(--info);    }

.kpi-badge {
    font-size: 11px; font-weight: 600;
    padding: 3px 8px; border-radius: 20px;
}
.kpi-badge.up   { background: var(--success-light); color: var(--success); }
.kpi-badge.down { background: var(--danger-light);  color: var(--danger);  }
.kpi-badge.warn { background: var(--warning-light); color: var(--warning); }
.kpi-badge.info { background: var(--info-light);    color: var(--info);    }

.kpi-value {
    font-size: 24px; font-weight: 700;
    color: var(--gray-900); line-height: 1.1;
}
.kpi-label {
    font-size: 12px; font-weight: 500;
    color: var(--gray-500); text-transform: uppercase; letter-spacing: 0.5px;
}
.kpi-sub {
    font-size: 12px; color: var(--gray-400);
    border-top: 1px solid var(--gray-100);
    padding-top: 8px; margin-top: 2px;
}

/* ── Section Row ────────────────────────────────────────── */
.dash-row {
    display: grid;
    gap: 16px;
    margin-bottom: 20px;
}
.dash-row.col-2 { grid-template-columns: 1fr 1fr; }
.dash-row.col-3 { grid-template-columns: 1fr 1fr 1fr; }
.dash-row.col-60-40 { grid-template-columns: 60fr 40fr; }
.dash-row.col-40-60 { grid-template-columns: 40fr 60fr; }

/* ── Panel Card ─────────────────────────────────────────── */
.panel-card {
    background: var(--white);
    border-radius: var(--border-radius-lg);
    box-shadow: var(--shadow-md);
    overflow: hidden;
}
.panel-head {
    padding: 16px 20px;
    display: flex; align-items: center; justify-content: space-between;
    border-bottom: 1px solid var(--gray-100);
}
.panel-title {
    font-size: 14px; font-weight: 600;
    color: var(--gray-800);
    display: flex; align-items: center; gap: 8px;
}
.panel-title .dot {
    width: 8px; height: 8px; border-radius: 50%;
    display: inline-block;
}
.panel-body { padding: 16px 20px; }
.panel-foot {
    padding: 10px 20px;
    background: var(--gray-50);
    border-top: 1px solid var(--gray-100);
    text-align: right;
}

/* ── Rank Bars ──────────────────────────────────────────── */
.rank-item { margin-bottom: 14px; }
.rank-item:last-child { margin-bottom: 0; }
.rank-meta { display: flex; justify-content: space-between; margin-bottom: 5px; }
.rank-name { font-size: 13px; font-weight: 500; color: var(--gray-700); }
.rank-val  { font-size: 13px; font-weight: 600; color: var(--gray-800); }
.rank-bar-bg {
    height: 7px; background: var(--gray-100);
    border-radius: 10px; overflow: hidden;
}
.rank-bar-fill {
    height: 100%; border-radius: 10px;
    transition: width 0.8s ease;
}

/* ── Alerta Estoque ─────────────────────────────────────── */
.stock-alert-item {
    display: flex; align-items: center;
    padding: 10px 0;
    border-bottom: 1px solid var(--gray-100);
    gap: 12px;
}
.stock-alert-item:last-child { border-bottom: none; }
.stock-dot {
    width: 10px; height: 10px; border-radius: 50%; flex-shrink: 0;
}
.stock-name { flex: 1; font-size: 13px; font-weight: 500; color: var(--gray-700); }
.stock-qty {
    font-size: 12px; font-weight: 700;
    padding: 3px 10px; border-radius: 20px;
    white-space: nowrap;
}
.stock-qty.zero  { background: var(--danger-light);  color: var(--danger); }
.stock-qty.low   { background: var(--warning-light); color: var(--warning); }
.stock-qty.ok    { background: var(--success-light); color: var(--success); }

/* ── Tabela Vendas Recentes ─────────────────────────────── */
.venda-row {
    display: grid;
    grid-template-columns: 60px 90px 1fr auto;
    align-items: center;
    gap: 12px;
    padding: 11px 0;
    border-bottom: 1px solid var(--gray-100);
}
.venda-row:last-child { border-bottom: none; }
.venda-codigo {
    font-size: 12px; font-weight: 700;
    color: var(--primary);
    background: var(--primary-bg);
    padding: 3px 8px; border-radius: 6px;
    text-align: center;
}
.venda-data  { font-size: 12px; color: var(--gray-500); }
.venda-cli   { font-size: 13px; font-weight: 500; color: var(--gray-700); }
.venda-total { font-size: 14px; font-weight: 700; color: var(--gray-900); white-space: nowrap; }

/* ── Financeiro Resumo ──────────────────────────────────── */
.fin-row {
    display: flex; align-items: center; justify-content: space-between;
    padding: 13px 0;
    border-bottom: 1px solid var(--gray-100);
}
.fin-row:last-child { border-bottom: none; }
.fin-label { font-size: 13px; color: var(--gray-600); display: flex; align-items: center; gap: 8px; }
.fin-label span { font-size: 15px; }
.fin-value { font-size: 15px; font-weight: 700; }

/* ── Margem Gauge ───────────────────────────────────────── */
.margem-wrap { text-align: center; padding: 8px 0 4px; }
.margem-val  { font-size: 38px; font-weight: 800; color: var(--gray-900); line-height: 1; }
.margem-desc { font-size: 12px; color: var(--gray-500); margin-top: 4px; }
.margem-bar-bg {
    height: 12px; background: var(--gray-100);
    border-radius: 10px; margin: 14px 0 6px; overflow: hidden;
}
.margem-bar-fill {
    height: 100%; border-radius: 10px;
    background: linear-gradient(90deg, var(--success), #34d399);
    transition: width 1s ease;
}

/* ── Empty State ────────────────────────────────────────── */
.empty-state {
    text-align: center; padding: 28px 16px;
    color: var(--gray-400); font-size: 13px;
}
.empty-state .glyphicon { font-size: 28px; margin-bottom: 8px; display: block; }

/* ── Responsive ─────────────────────────────────────────── */
@media (max-width: 992px) {
    .kpi-grid { grid-template-columns: repeat(2, 1fr); }
    .dash-row.col-2,
    .dash-row.col-3,
    .dash-row.col-60-40,
    .dash-row.col-40-60 { grid-template-columns: 1fr; }
}
@media (max-width: 576px) {
    .kpi-grid { grid-template-columns: 1fr; }
}
</style>

<div class="dash-wrap">

    <!-- ── Saudação ── -->
    <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:20px;">
        <div>
            <h2 style="margin:0; font-size:20px; font-weight:700; color:var(--gray-900);">
                Painel Executivo
            </h2>
            <p style="margin:4px 0 0; color:var(--gray-500); font-size:13px;">
                <?php echo $mesAtual . ' de ' . date('Y'); ?> · Atualizado em <?php echo date('H:i'); ?>
            </p>
        </div>
        <div style="font-size:12px; color:var(--gray-400); text-align:right;">
            <span class="glyphicon glyphicon-ok-circle" style="color:var(--success);"></span>
            Sistema operacional
        </div>
    </div>

    <!-- ── KPI Cards (linha 1) ── -->
    <div class="kpi-grid">

        <div class="kpi-card green">
            <div class="kpi-top">
                <div class="kpi-icon"><span class="glyphicon glyphicon-usd"></span></div>
                <span class="kpi-badge up">&#x25B2; Hoje</span>
            </div>
            <div class="kpi-value">R$ <?php echo number_format($vendasHoje, 2, ',', '.'); ?></div>
            <div class="kpi-label">Vendas Hoje</div>
            <div class="kpi-sub">Receita acumulada no dia atual</div>
        </div>

        <div class="kpi-card blue">
            <div class="kpi-top">
                <div class="kpi-icon"><span class="glyphicon glyphicon-calendar"></span></div>
                <span class="kpi-badge info"><?php echo $mesAtual; ?></span>
            </div>
            <div class="kpi-value">R$ <?php echo number_format($vendasMes, 2, ',', '.'); ?></div>
            <div class="kpi-label">Vendas no Mês</div>
            <div class="kpi-sub"><?php echo $qtdVendasMes; ?> transaç<?php echo $qtdVendasMes == 1 ? 'ão' : 'ões'; ?> realizadas</div>
        </div>

        <div class="kpi-card amber">
            <div class="kpi-top">
                <div class="kpi-icon"><span class="glyphicon glyphicon-shopping-cart"></span></div>
                <span class="kpi-badge warn">Média</span>
            </div>
            <div class="kpi-value">R$ <?php echo number_format($ticketMedio, 2, ',', '.'); ?></div>
            <div class="kpi-label">Ticket Médio</div>
            <div class="kpi-sub">Por transação neste mês</div>
        </div>

        <div class="kpi-card <?php echo $baixoEstoque > 0 ? 'red' : 'green'; ?>">
            <div class="kpi-top">
                <div class="kpi-icon"><span class="glyphicon glyphicon-exclamation-sign"></span></div>
                <span class="kpi-badge <?php echo $baixoEstoque > 0 ? 'down' : 'up'; ?>">
                    <?php echo $baixoEstoque > 0 ? 'Atenção' : 'OK'; ?>
                </span>
            </div>
            <div class="kpi-value"><?php echo $baixoEstoque; ?></div>
            <div class="kpi-label">Baixo Estoque</div>
            <div class="kpi-sub">Produtos com menos de 20 un.</div>
        </div>

    </div>

    <!-- ── Linha 2: Financeiro + Margem ── -->
    <div class="dash-row col-60-40">

        <!-- Resumo Financeiro -->
        <div class="panel-card">
            <div class="panel-head">
                <div class="panel-title">
                    <span class="dot" style="background:var(--primary);"></span>
                    Resumo Financeiro
                </div>
                <span style="font-size:11px; color:var(--gray-400);">Acumulado geral</span>
            </div>
            <div class="panel-body">
                <div class="fin-row">
                    <span class="fin-label">
                        <span class="glyphicon glyphicon-arrow-down" style="color:var(--info);"></span>
                        Custo Total de Entradas
                    </span>
                    <span class="fin-value" style="color:var(--info);">R$ <?php echo number_format($totalEntradas, 2, ',', '.'); ?></span>
                </div>
                <div class="fin-row">
                    <span class="fin-label">
                        <span class="glyphicon glyphicon-arrow-up" style="color:var(--success);"></span>
                        Receita Total de Vendas
                    </span>
                    <span class="fin-value" style="color:var(--success);">R$ <?php echo number_format($totalSaidas, 2, ',', '.'); ?></span>
                </div>
                <div class="fin-row">
                    <span class="fin-label">
                        <span class="glyphicon glyphicon-piggy-bank" style="color:<?php echo $lucroTotal >= 0 ? 'var(--success)' : 'var(--danger)'; ?>;"></span>
                        Lucro Líquido
                    </span>
                    <span class="fin-value" style="color:<?php echo $lucroTotal >= 0 ? 'var(--success)' : 'var(--danger)'; ?>;">
                        R$ <?php echo number_format($lucroTotal, 2, ',', '.'); ?>
                    </span>
                </div>
            </div>
        </div>

        <!-- Margem de Lucro -->
        <div class="panel-card">
            <div class="panel-head">
                <div class="panel-title">
                    <span class="dot" style="background:var(--success);"></span>
                    Margem de Lucro
                </div>
            </div>
            <div class="panel-body">
                <div class="margem-wrap">
                    <div class="margem-val" style="color:<?php echo $margemLucro >= 0 ? 'var(--success)' : 'var(--danger)'; ?>;">
                        <?php echo number_format($margemLucro, 1, ',', '.'); ?>%
                    </div>
                    <div class="margem-desc">sobre o total de vendas</div>
                    <div class="margem-bar-bg">
                        <div class="margem-bar-fill" style="width:<?php echo min(100, max(0, $margemLucro)); ?>%; background: <?php echo $margemLucro >= 0 ? 'linear-gradient(90deg, var(--success), #34d399)' : 'var(--danger)'; ?>;"></div>
                    </div>
                    <div style="display:flex; justify-content:space-between; font-size:11px; color:var(--gray-400);">
                        <span>0%</span><span>50%</span><span>100%</span>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- ── Linha 3: Top Produtos + Categorias ── -->
    <div class="dash-row col-2">

        <!-- Top 5 Produtos -->
        <div class="panel-card">
            <div class="panel-head">
                <div class="panel-title">
                    <span class="dot" style="background:var(--primary);"></span>
                    Top 5 Produtos Mais Vendidos
                </div>
                <span style="font-size:11px; color:var(--gray-400);">por quantidade</span>
            </div>
            <div class="panel-body">
                <?php if(empty($topProdutos)): ?>
                <div class="empty-state">
                    <span class="glyphicon glyphicon-stats"></span>
                    Nenhuma venda registrada ainda
                </div>
                <?php else: ?>
                <?php $colors = ['#2563eb','#0891b2','#059669','#d97706','#7c3aed'];
                      foreach($topProdutos as $i => $p):
                        $pct = round(($p['total_vendido'] / $maxVendido) * 100);
                ?>
                <div class="rank-item">
                    <div class="rank-meta">
                        <span class="rank-name"><?php echo htmlspecialchars($p['nome']); ?></span>
                        <span class="rank-val"><?php echo $p['total_vendido']; ?> un.</span>
                    </div>
                    <div class="rank-bar-bg">
                        <div class="rank-bar-fill" style="width:<?php echo $pct; ?>%; background:<?php echo $colors[$i % 5]; ?>;"></div>
                    </div>
                </div>
                <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>

        <!-- Faturamento por Categoria -->
        <div class="panel-card">
            <div class="panel-head">
                <div class="panel-title">
                    <span class="dot" style="background:var(--warning);"></span>
                    Faturamento por Categoria
                </div>
                <span style="font-size:11px; color:var(--gray-400);">em R$</span>
            </div>
            <div class="panel-body">
                <?php if(empty($fatCategorias)): ?>
                <div class="empty-state">
                    <span class="glyphicon glyphicon-list"></span>
                    Nenhum dado disponível
                </div>
                <?php else: ?>
                <?php $colors2 = ['#d97706','#f59e0b','#fbbf24','#fcd34d','#fde68a'];
                      foreach($fatCategorias as $i => $cat):
                        $pct2 = round(($cat['faturamento'] / $maxFat) * 100);
                ?>
                <div class="rank-item">
                    <div class="rank-meta">
                        <span class="rank-name"><?php echo htmlspecialchars($cat['nome_categoria']); ?></span>
                        <span class="rank-val">R$ <?php echo number_format($cat['faturamento'], 2, ',', '.'); ?></span>
                    </div>
                    <div class="rank-bar-bg">
                        <div class="rank-bar-fill" style="width:<?php echo $pct2; ?>%; background:<?php echo $colors2[$i % 5]; ?>;"></div>
                    </div>
                </div>
                <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>

    </div>

    <!-- ── Linha 4: Vendas Recentes + Alertas Estoque ── -->
    <div class="dash-row col-60-40">

        <!-- Últimas Vendas -->
        <div class="panel-card">
            <div class="panel-head">
                <div class="panel-title">
                    <span class="dot" style="background:var(--success);"></span>
                    Vendas Recentes
                </div>
                <span style="font-size:11px; color:var(--gray-400);">últimas 6 transações</span>
            </div>
            <div class="panel-body" style="padding: 8px 20px;">
                <?php if(empty($ultVendas)): ?>
                <div class="empty-state">
                    <span class="glyphicon glyphicon-time"></span>
                    Nenhuma venda registrada
                </div>
                <?php else: ?>
                <?php foreach($ultVendas as $v): ?>
                <div class="venda-row">
                    <span class="venda-codigo">#<?php echo $v['codigo_venda']; ?></span>
                    <span class="venda-data"><?php echo date('d/m/Y', strtotime($v['dataCompra'])); ?></span>
                    <span class="venda-cli">
                        <?php
                            $nomeCliente = $objV->nomeCliente($v['id_cliente']);
                            echo trim($nomeCliente) !== '' ? htmlspecialchars($nomeCliente) : '<span style="color:var(--gray-400);">S/ cliente</span>';
                        ?>
                    </span>
                    <span class="venda-total">R$ <?php echo number_format($v['total'], 2, ',', '.'); ?></span>
                </div>
                <?php endforeach; ?>
                <?php endif; ?>
            </div>
            <div class="panel-foot">
                <a href="vendas.php" class="btn-modern btn-primary-modern btn-sm-modern">
                    Ver todas as vendas &rarr;
                </a>
            </div>
        </div>

        <!-- Alertas de Estoque -->
        <div class="panel-card">
            <div class="panel-head">
                <div class="panel-title">
                    <span class="dot" style="background:var(--danger);"></span>
                    Alertas de Estoque
                </div>
                <?php if($baixoEstoque > 0): ?>
                <span style="font-size:11px; background:var(--danger-light); color:var(--danger); padding:3px 8px; border-radius:20px; font-weight:600;">
                    <?php echo $baixoEstoque; ?> item<?php echo $baixoEstoque > 1 ? 's' : ''; ?>
                </span>
                <?php endif; ?>
            </div>
            <div class="panel-body" style="padding: 8px 20px; max-height: 280px; overflow-y: auto;">
                <?php if(empty($prodBaixo)): ?>
                <div class="empty-state">
                    <span class="glyphicon glyphicon-ok-circle" style="color:var(--success);"></span>
                    Todos os produtos estão bem estocados!
                </div>
                <?php else: ?>
                <?php foreach($prodBaixo as $p):
                    $qty = (int)$p['estoque'];
                    if ($qty == 0) { $cls = 'zero'; $label = 'Sem estoque'; }
                    elseif ($qty <= 5)  { $cls = 'zero'; $label = $qty . ' un.'; }
                    elseif ($qty <= 10) { $cls = 'low';  $label = $qty . ' un.'; }
                    else                { $cls = 'ok';   $label = $qty . ' un.'; }
                    $dotColor = $cls == 'zero' ? 'var(--danger)' : ($cls == 'low' ? 'var(--warning)' : 'var(--success)');
                ?>
                <div class="stock-alert-item">
                    <span class="stock-dot" style="background:<?php echo $dotColor; ?>;"></span>
                    <span class="stock-name"><?php echo htmlspecialchars($p['nome']); ?></span>
                    <span class="stock-qty <?php echo $cls; ?>"><?php echo $label; ?></span>
                </div>
                <?php endforeach; ?>
                <?php endif; ?>
            </div>
            <div class="panel-foot">
                <a href="entradaEstoque.php" class="btn-modern btn-warning-modern btn-sm-modern">
                    Registrar entrada &rarr;
                </a>
            </div>
        </div>

    </div>

</div>
