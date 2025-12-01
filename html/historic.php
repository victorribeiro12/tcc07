<?php
// Define the activity data
$activities = [
    ['id' => 1, 'type' => 'exercise', 'title' => 'Exercício: Criar componentes funcionais', 'course' => 'React Avançado', 'timestamp' => 'Hoje às 14:30', 'score' => 95, 'progress' => 100],
    ['id' => 2, 'type' => 'exercise', 'title' => 'Quiz: Hooks e State Management', 'course' => 'React Avançado', 'timestamp' => 'Hoje às 13:15', 'score' => 88, 'progress' => 100],
    ['id' => 3, 'type' => 'exercise', 'title' => 'Prática: Sistema de Login', 'course' => 'JavaScript Avançado', 'timestamp' => 'Ontem às 18:45', 'score' => 92, 'progress' => 100],
    ['id' => 4, 'type' => 'exercise', 'title' => 'Desafio: API com Promises', 'course' => 'JavaScript Avançado', 'timestamp' => 'Ontem às 16:20', 'score' => 78, 'progress' => 100],
    ['id' => 5, 'type' => 'exercise', 'title' => 'Quiz: Fundamentos de CSS', 'course' => 'CSS Completo', 'timestamp' => '2 dias atrás', 'score' => 88, 'progress' => 100],
    ['id' => 6, 'type' => 'exercise', 'title' => 'Exercício: Grid Layout Responsivo', 'course' => 'CSS Completo', 'timestamp' => '2 dias atrás', 'score' => 100, 'progress' => 100],
    ['id' => 7, 'type' => 'exercise', 'title' => 'Prática: Flexbox na prática', 'course' => 'CSS Completo', 'timestamp' => '3 dias atrás', 'score' => 85, 'progress' => 100],
    ['id' => 8, 'type' => 'exercise', 'title' => 'Quiz: Seletores e Pseudo-classes', 'course' => 'CSS Completo', 'timestamp' => '3 dias atrás', 'score' => 90, 'progress' => 100],
    ['id' => 9, 'type' => 'exercise', 'title' => 'Desafio: Formulário Completo', 'course' => 'HTML e CSS', 'timestamp' => '4 dias atrás', 'score' => 82, 'progress' => 100],
    ['id' => 10, 'type' => 'exercise', 'title' => 'Exercício: Manipulação do DOM', 'course' => 'JavaScript Básico', 'timestamp' => '5 dias atrás', 'score' => 94, 'progress' => 100],
    ['id' => 11, 'type' => 'certificate', 'title' => 'Certificado de Conclusão', 'course' => 'JavaScript Básico', 'timestamp' => '5 dias atrás', 'progress' => 100],
    ['id' => 12, 'type' => 'reading', 'title' => 'Material de apoio: Flexbox', 'course' => 'CSS Completo', 'timestamp' => '6 dias atrás', 'progress' => 100],
];

$exerciseActivities = array_filter($activities, function($a) { return $a['type'] === 'exercise'; });
$totalScores = array_reduce($exerciseActivities, function($acc, $a) { return $acc + ($a['score'] ?? 0); }, 0);
$avgScore = count($exerciseActivities) > 0 ? round($totalScores / count($exerciseActivities)) : 0;

$stats = [
    'total' => count($activities),
    'exercises' => count($exerciseActivities),
    'certificates' => count(array_filter($activities, function($a) { return $a['type'] === 'certificate'; })),
    'avgScore' => $avgScore,
];

function get_score_class($score) {
    if ($score >= 90) return 'text-green';
    if ($score >= 80) return 'text-yellow';
    return 'text-red';
}

function get_type_color_class($type) {
    switch($type) {
        case 'exercise': return 'border-emerald';
        case 'certificate': return 'border-amber';
        case 'reading': return 'border-sky';
        default: return 'border-gray';
    }
}

function get_type_name($type) {
    switch($type) {
        case 'exercise': return 'Exercício Concluído';
        case 'certificate': return 'Certificado Obtido';
        case 'reading': return 'Leitura Completa';
        default: return 'Atividade';
    }
}

function get_icon_name($type) {
    switch($type) {
        case 'exercise': return 'check-circle';
        case 'certificate': return 'award';
        case 'reading': return 'file-text';
        default: return 'book-open';
    }
}

function get_svg_icon($name, $class, $size = 24) {
    $svgs = [
        'clock' => '<svg xmlns="http://www.w3.org/2000/svg" width="'.$size.'" height="'.$size.'" fill="none" stroke="currentColor" stroke-width="2" class="'.$class.'"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>',
        'book-open' => '<svg xmlns="http://www.w3.org/2000/svg" width="'.$size.'" height="'.$size.'" fill="none" stroke="currentColor" stroke-width="2" class="'.$class.'"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path></svg>',
        'check-circle' => '<svg xmlns="http://www.w3.org/2000/svg" width="'.$size.'" height="'.$size.'" fill="none" stroke="currentColor" stroke-width="2" class="'.$class.'"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>',
        'award' => '<svg xmlns="http://www.w3.org/2000/svg" width="'.$size.'" height="'.$size.'" fill="none" stroke="currentColor" stroke-width="2" class="'.$class.'"><circle cx="12" cy="8" r="7"></circle><polyline points="8.21 13.89 7 23 12 20 17 23 15.79 13.88"></polyline></svg>',
        'file-text' => '<svg xmlns="http://www.w3.org/2000/svg" width="'.$size.'" height="'.$size.'" fill="none" stroke="currentColor" stroke-width="2" class="'.$class.'"><path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7z"></path><line x1="16" y1="13" x2="8" y2="13"></line></svg>',
        'filter' => '<svg xmlns="http://www.w3.org/2000/svg" width="'.$size.'" height="'.$size.'" fill="none" stroke="currentColor" stroke-width="2" class="'.$class.'"><polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"></polygon></svg>',
        'zap' => '<svg xmlns="http://www.w3.org/2000/svg" width="'.$size.'" height="'.$size.'" fill="none" stroke="currentColor" stroke-width="2" class="'.$class.'"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"></polygon></svg>',
    ];
    return $svgs[strtolower($name)] ?? '';
}

$filteredActivities = $activities;
$filterTypes = [
    'all' => 'Todas',
    'exercise' => 'Exercícios',
    'certificate' => 'Certificados',
    'reading' => 'Leituras',
];
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Histórico de Atividades</title>
    <link rel="stylesheet" href="teste.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800;900&display=swap" rel="stylesheet">
</head>

<body>
    <div class="container">
        <div class="max-w-5xl mx-auto">

            <!-- Header -->
            <div class="header">
                <div class="header-content">
                    <?php echo get_svg_icon('clock', 'text-indigo', 36); ?>
                    <h1 class="text-3xl sm:text-4xl font-extrabold text-gray-800 tracking-tight">
                        Histórico de Atividades
                    </h1>
                </div>
                <p class="header-subtitle">Um panorama completo do seu desempenho e marcos de aprendizagem.</p>
            </div>

            <!-- Statistics Cards -->
            <div class="stat-grid">
                <div class="stat-card border-indigo">
                    <div class="stat-card-content">
                        <div>
                            <p class="stat-card-title">Total</p>
                            <p class="stat-card-value"><?php echo $stats['total']; ?></p>
                        </div>
                        <div class="stat-card-icon bg-gray-50">
                            <?php echo get_svg_icon('book-open', 'text-indigo', 32); ?>
                        </div>
                    </div>
                </div>

                <div class="stat-card border-emerald">
                    <div class="stat-card-content">
                        <div>
                            <p class="stat-card-title">Exercícios</p>
                            <p class="stat-card-value"><?php echo $stats['exercises']; ?></p>
                        </div>
                        <div class="stat-card-icon bg-gray-50">
                            <?php echo get_svg_icon('check-circle', 'text-emerald', 32); ?>
                        </div>
                    </div>
                </div>

                <div class="stat-card border-amber">
                    <div class="stat-card-content">
                        <div>
                            <p class="stat-card-title">Certificados</p>
                            <p class="stat-card-value"><?php echo $stats['certificates']; ?></p>
                        </div>
                        <div class="stat-card-icon bg-gray-50">
                            <?php echo get_svg_icon('award', 'text-amber', 32); ?>
                        </div>
                    </div>
                </div>

                <div class="stat-card border-red">
                    <div class="stat-card-content">
                        <div>
                            <p class="stat-card-title">Nota Média</p>
                            <p class="stat-card-value"><?php echo $stats['avgScore']; ?>%</p>
                        </div>
                        <div class="stat-card-icon bg-gray-50">
                            <?php echo get_svg_icon('zap', 'text-red', 32); ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filter Bar (MODIFICADO COM CAMPO DE BUSCA) -->
            <div class="filter-bar">
                <?php echo get_svg_icon('filter', 'text-gray-600', 20); ?>
                <span class="text-gray-700 font-bold whitespace-nowrap">Filtrar:</span>

                <?php foreach ($filterTypes as $key => $label): ?>
                    <button class="filter-button <?php echo $key === 'all' ? 'active-indigo' : ''; ?>">
                        <?php echo $label; ?>
                    </button>
                <?php endforeach; ?>

                <!-- ADICIONADO: Campo de Busca -->
                <input 
                    type="text" 
                    id="searchInput" 
                    placeholder="Buscar atividade..." 
                    style="padding: 8px; border: 1px solid #ccc; border-radius: 8px; margin-left: 12px;"
                >
            </div>

            <!-- Activities List -->
            <div class="activities-list-container">
                <h2 class="activities-list-title">
                    Atividades Recentes (<?php echo count($filteredActivities); ?>)
                </h2>

                <div class="activities-list-items">
                    <?php foreach ($filteredActivities as $activity): ?>
                        <?php 
                            $isExercise = $activity['type'] === 'exercise';
                            $scoreClass = $isExercise ? get_score_class($activity['score']) : '';
                            $typeColor = get_type_color_class($activity['type']);
                        ?>
                        <div class="activity-item">
                            <div class="activity-item-indicator <?php echo $typeColor; ?>"></div>

                            <div class="activity-item-icon bg-gray-50">
                                <?php echo get_svg_icon(get_icon_name($activity['type']), 'text-' . str_replace('border-', '', $typeColor), 24); ?>
                            </div>

                            <div class="activity-item-content">
                                <div class="activity-item-header">
                                    <div class="activity-item-info">
                                        <span class="activity-item-type text-indigo">
                                            <?php echo get_type_name($activity['type']); ?>
                                        </span>
                                        <h3 class="activity-item-title" title="<?php echo $activity['title']; ?>">
                                            <?php echo $activity['title']; ?>
                                        </h3>
                                        <p class="activity-item-course">
                                            <?php echo $activity['course']; ?>
                                        </p>
                                    </div>

                                    <span class="activity-item-timestamp">
                                        <?php echo get_svg_icon('clock', 'inline-icon', 12); ?> <?php echo $activity['timestamp']; ?>
                                    </span>
                                </div>

                                <div class="activity-item-footer">
                                    <?php if ($isExercise && isset($activity['score'])): ?>
                                        <span class="activity-item-score <?php echo $scoreClass; ?>">
                                            <?php echo get_svg_icon('zap', 'inline-icon', 16); ?> Nota: <?php echo $activity['score']; ?>%
                                        </span>
                                    <?php endif; ?>

                                    <?php if (isset($activity['progress'])): ?>
                                        <div class="activity-item-progress-wrap">
                                            <span class="activity-item-progress-label">Progresso:</span>
                                            <div class="activity-item-progress-bar">
                                                <div class="activity-item-progress-fill" style="width: <?php echo $activity['progress']; ?>%;"></div>
                                            </div>
                                            <span class="activity-item-progress-value"><?php echo $activity['progress']; ?>%</span>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

        </div>
    </div>

    <!-- SCRIPT ADICIONADO CORRETAMENTE -->
    <script src="activityFilter.js"></script>

<!-- Code injected by live-server -->
<script>
	// <![CDATA[  <-- For SVG support
	if ('WebSocket' in window) {
		(function () {
			function refreshCSS() {
				var sheets = [].slice.call(document.getElementsByTagName("link"));
				var head = document.getElementsByTagName("head")[0];
				for (var i = 0; i < sheets.length; ++i) {
					var elem = sheets[i];
					var parent = elem.parentElement || head;
					parent.removeChild(elem);
					var rel = elem.rel;
					if (elem.href && typeof rel != "string" || rel.length == 0 || rel.toLowerCase() == "stylesheet") {
						var url = elem.href.replace(/(&|\?)_cacheOverride=\d+/, '');
						elem.href = url + (url.indexOf('?') >= 0 ? '&' : '?') + '_cacheOverride=' + (new Date().valueOf());
					}
					parent.appendChild(elem);
				}
			}
			var protocol = window.location.protocol === 'http:' ? 'ws://' : 'wss://';
			var address = protocol + window.location.host + window.location.pathname + '/ws';
			var socket = new WebSocket(address);
			socket.onmessage = function (msg) {
				if (msg.data == 'reload') window.location.reload();
				else if (msg.data == 'refreshcss') refreshCSS();
			};
			if (sessionStorage && !sessionStorage.getItem('IsThisFirstTime_Log_From_LiveServer')) {
				console.log('Live reload enabled.');
				sessionStorage.setItem('IsThisFirstTime_Log_From_LiveServer', true);
			}
		})();
	}
	else {
		console.error('Upgrade your browser. This Browser is NOT supported WebSocket for Live-Reloading.');
	}
	// ]]>
</script>
</body>
</html>
