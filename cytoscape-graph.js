// Calculate probability (same as PHP logic)
function calculateProbability(paths) {
	let totalProb = 0;
	paths.forEach(path => {
		const L_count = (path.match(/L/g) || []).length;
		const W_count = (path.match(/W/g) || []).length;
		const R_count = (path.match(/R/g) || []).length;
		const P_count = (path.match(/P/g) || []).length;
		const S_count = (path.match(/S/g) || []).length;
		const E_count = (path.match(/E/g) || []).length;
		const B_count = (path.match(/B/g) || []).length;
		const T_count = (path.match(/T/g) || []).length;

		totalProb += Math.pow(2/3, L_count) *
					 Math.pow(1/3, W_count) *
					 Math.pow(1/3, R_count) *
					 Math.pow(1/3, P_count) *
					 Math.pow(1/3, S_count) *
					 Math.pow(1/2, E_count) *
					 Math.pow(1/2, B_count) *
					 Math.pow(1/2, T_count);
	});
	return totalProb;
}

// Initialize graph
function initializeGraph(videoData, firstVideoId, firstVideoViews) {
	// Convert to Cytoscape format
	const elements = [];

	// Find min and max views for better scaling
	let minViews = Infinity;
	let maxViews = 0;
	Object.keys(videoData).forEach(videoId => {
		const views = parseInt(videoData[videoId].views) || 1;
		minViews = Math.min(minViews, views);
		maxViews = Math.max(maxViews, views);
	});

	// Add nodes
	Object.keys(videoData).forEach(videoId => {
		const video = videoData[videoId];
		const views = parseInt(video.views) || 1;
		const normalizedSize = views / maxViews;

		// Check if arrays exist and have length
		const hasParents = Array.isArray(video.parents) && video.parents.length > 0;
		const hasChildren = Array.isArray(video.children) && video.children.length > 0;

		const isStart = !hasParents; // No parents = start node
		const isEnd = !hasChildren;   // No children = end node

		elements.push({
			data: {
				id: videoId,
				label: videoId,
				views: views,
				paths: video.paths,
				probability: calculateProbability(video.paths),
				ending: video.ending,
				children: video.children,
				parents: video.parents,
				childPath: video.child_path,
				normalizedSize: normalizedSize,
				isStart: isStart,
				isEnd: isEnd
			}
		});
	});

	// Add edges
	Object.keys(videoData).forEach(videoId => {
		const video = videoData[videoId];
		video.children.forEach((childId, index) => {
			const edgeType = video.child_path[index];
			let color = '#666';

			if (edgeType === 'W') color = '#22c55e'; // Green for Win
			else if (edgeType === 'L') color = '#ef4444'; // Red for Lose

			elements.push({
				data: {
					id: `${videoId}-${childId}`,
					source: videoId,
					target: childId,
					edgeType: edgeType,
					color: color,
					width: Math.max(1, (video.views / firstVideoViews) * 8)
				}
			});
		});
	});

	// Initialize Cytoscape
	const cy = cytoscape({
		container: document.getElementById('cy'),
		elements: elements,
		style: [
			{
				selector: 'node',
				style: {
					'background-color': '#9ca3af', // Grey/neutral for middle nodes
					'label': 'data(label)',
					// Size nodes with square root scaling for more visible differences
					// Range: 20px to 200px
					'width': ele => 20 + (Math.sqrt(ele.data('normalizedSize')) * 180),
					'height': ele => 20 + (Math.sqrt(ele.data('normalizedSize')) * 180),
					// Scale font size with node size
					'font-size': ele => Math.max(7, 8 + (Math.sqrt(ele.data('normalizedSize')) * 14)),
					'text-valign': 'center',
					'text-halign': 'center',
					'color': '#333',
					'text-outline-width': 2,
					'text-outline-color': '#fff',
					'border-width': 2,
					'border-color': '#fff',
					'font-weight': 'bold'
				}
			},
			{
				selector: 'node[?isEnd]',
				style: {
					'background-color': '#fbbf24', // Gold for endings
					'border-width': 4,
					'border-color': '#f59e0b'
				}
			},
			{
				selector: 'node[?isStart]',
				style: {
					'background-color': '#22c55e', // Green for start
					'border-width': 4,
					'border-color': '#16a34a'
				}
			},
			{
				selector: 'edge',
				style: {
					'width': 'data(width)',
					'line-color': 'data(color)',
					'target-arrow-color': 'data(color)',
					'target-arrow-shape': 'triangle',
					'curve-style': 'bezier',
					'opacity': 0.6
				}
			},
			{
				selector: '.selected',
				style: {
					'background-color': '#f59e0b',
					'border-color': '#ea580c',
					'border-width': 6,
					'z-index': 9999
				}
			},
			{
				selector: '.connected',
				style: {
					'background-color': '#60a5fa',
					'border-color': '#3b82f6',
					'border-width': 4,
					'z-index': 9998
				}
			},
			{
				selector: '.dimmed',
				style: {
					'opacity': 0.2
				}
			},
			{
				selector: '.path-highlight',
				style: {
					'width': ele => ele.data('width') * 2.5,
					'opacity': 1,
					'z-index': 9999
				}
			}
		],
		layout: {
			name: 'breadthfirst',
			directed: true,
			roots: `#${firstVideoId}`,
			spacingFactor: 1.5,
			avoidOverlap: true,
			nodeDimensionsIncludeLabels: true
		},
		minZoom: 0.1,
		maxZoom: 3
	});

	return cy;
}

// Node interaction functions
function showInfoPanel(node, firstVideoViews) {
	const data = node.data();
	const infoPanel = document.getElementById('info-panel');
	const infoContent = document.getElementById('info-content');
	const infoTitle = document.getElementById('info-video-id');

	infoTitle.textContent = data.id;

	let html = `
		<div class="stat-row">
			<span class="stat-label">Views:</span>
			<span class="stat-value">${Number(data.views).toLocaleString()}</span>
		</div>
		<div class="stat-row">
			<span class="stat-label">% of Start:</span>
			<span class="stat-value">${((data.views / firstVideoViews) * 100).toFixed(2)}%</span>
		</div>
		<div class="stat-row">
			<span class="stat-label">Theoretical %:</span>
			<span class="stat-value">${(data.probability * 100).toFixed(2)}%</span>
		</div>
	`;

	if (data.ending) {
		html += '<div class="stat-row"><span class="stat-label">Type:</span><span class="stat-value" style="color: #f59e0b; font-weight: bold;">ENDING</span></div>';
	}

	if (data.children && data.children.length > 0) {
		html += '<h4 style="margin-top: 15px; color: #667eea; font-size: 14px;">Children:</h4>';
		data.children.forEach((childId, idx) => {
			const edgeType = data.childPath[idx];
			let label = edgeType;
			switch(edgeType) {
				case 'L': label = 'Lose'; break;
				case 'W': label = 'Win'; break;
				case 'E': label = 'End'; break;
				case 'B': label = 'Billion'; break;
				case 'T': label = 'Trillion'; break;
				case 'R': label = 'Rock'; break;
				case 'P': label = 'Paper'; break;
				case 'S': label = 'Scissors'; break;
			}
			html += `<div class="stat-row" style="cursor: pointer;" onclick="navigateToNode('${childId}')">
				<span class="stat-label">${label}:</span>
				<span class="stat-value" style="color: #667eea;">${childId} →</span>
			</div>`;
		});
	}

	if (data.parents && data.parents.length > 0) {
		html += '<h4 style="margin-top: 15px; color: #667eea; font-size: 14px;">Parents:</h4>';
		data.parents.forEach(parentId => {
			html += `<div class="stat-row" style="cursor: pointer;" onclick="navigateToNode('${parentId}')">
				<span class="stat-value" style="color: #667eea;">← ${parentId}</span>
			</div>`;
		});
	}

	html += `<div style="margin-top: 15px;">
		<a href="https://youtube.com/watch?v=${data.id}" target="_blank" class="control-btn" style="display: inline-block; text-decoration: none; width: 100%; text-align: center;">
			<i class="bi bi-play-circle"></i> Watch on YouTube
		</a>
	</div>`;

	if (data.paths && data.paths.length > 0) {
		html += `<h4 style="margin-top: 15px; color: #667eea; font-size: 14px;">Paths (${data.paths.length}):</h4>`;
		html += '<div style="max-height: 150px; overflow-y: auto; font-size: 11px; font-family: monospace; background: #f8f9fa; padding: 10px; border-radius: 4px;">';
		data.paths.slice(0, 20).forEach(path => {
			html += `${path}<br/>`;
		});
		if (data.paths.length > 20) {
			html += `<i>... and ${data.paths.length - 20} more</i>`;
		}
		html += '</div>';
	}

	infoContent.innerHTML = html;
	infoPanel.classList.add('show');
}

function closeInfoPanel() {
	document.getElementById('info-panel').classList.remove('show');
}

function highlightNode(node, cy) {
	cy.elements().removeClass('selected connected dimmed path-highlight');

	// Highlight the selected node
	node.addClass('selected');

	// Highlight connected nodes (parents and children)
	const connectedNodes = node.neighborhood().nodes();
	connectedNodes.addClass('connected');

	// Dim everything else
	cy.elements().not(node).not(connectedNodes).not(node.connectedEdges()).addClass('dimmed');

	// Highlight edges (make them thicker but keep original colors)
	node.connectedEdges().addClass('path-highlight');
}

function clearHighlights(cy) {
	cy.elements().removeClass('selected connected dimmed path-highlight');
}

function navigateToNode(nodeId, cy) {
	const node = cy.$(`#${nodeId}`);
	if (node.length > 0) {
		cy.animate({
			center: { eles: node },
			zoom: 1.5,
			duration: 500
		});
		showInfoPanel(node, window.firstVideoViews);
		highlightNode(node, cy);
	}
}

function resetView(cy, firstVideoId) {
	clearHighlights(cy);
	closeInfoPanel();
	cy.animate({
		center: { eles: cy.$(`#${firstVideoId}`) },
		zoom: 1,
		duration: 500
	});
}

function fitToScreen(cy) {
	cy.fit(null, 50);
}

function centerOnStart(firstVideoId, cy) {
	navigateToNode(firstVideoId, cy);
}

function changeLayout(layoutName, cy, firstVideoId) {
	let layoutOptions = {
		name: layoutName,
		animate: true,
		animationDuration: 1000,
		spacingFactor: 1.5,
		avoidOverlap: true,
		nodeDimensionsIncludeLabels: true
	};

	if (layoutName === 'breadthfirst') {
		layoutOptions.directed = true;
		layoutOptions.roots = `#${firstVideoId}`;
	} else if (layoutName === 'cose') {
		layoutOptions.nodeRepulsion = 8000;
		layoutOptions.idealEdgeLength = 100;
	}

	cy.layout(layoutOptions).run();
}
