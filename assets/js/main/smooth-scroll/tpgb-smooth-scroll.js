let lenisInstance = null,
	isInitialized = false,
	retryCount = 0,
	keyboardCleanup = null;
const MAX_RETRIES = 5;

const easings = {
	linear: (t) => t,
	"ease-out-cubic": (t) => 1 - Math.pow(1 - t, 3),
	"ease-in-out": (t) =>
		t < 0.5 ? 2 * t * t : 1 - Math.pow(-2 * t + 2, 3) / 2,
	"ease-out-quart": (t) => 1 - Math.pow(1 - t, 4),
	"ease-in-cubic": (t) => t * t * t,
	"ease-out-quint": (t) => 1 - Math.pow(1 - t, 5),
};

function initializeScrollSystem() {
	if (isInitialized) return;

	const smoothScrollElement = document.querySelector(".tpgb-smooth-scroll");
	if (!smoothScrollElement) {
		if (retryCount < MAX_RETRIES) {
			retryCount++;
			setTimeout(initializeScrollSystem, 500);
		}
		return;
	}

	// Check if Lenis is available, if not retry
	if (typeof Lenis === "undefined") {
		if (retryCount < MAX_RETRIES) {
			retryCount++;
			setTimeout(initializeScrollSystem, 500);
		} else {
			console.error("Lenis library not found. Please ensure the minified Lenis JS file is loaded.");
		}
		return;
	}

	initLenis();
}

function initLenis() {
	if (lenisInstance) return;

	try {
		const config = getScrollConfig();
		lenisInstance = new Lenis({
			duration: config.animationTime
				? Math.max(0.1, config.animationTime / 5000)
				: 1.2,
			easing:
				config.easing === "custom"
					? new Function("t", "return " + config.customEasing)
					: easings[config.easing] || ((t) => t),
			direction: "vertical",
			gestureDirection: "vertical",
			smooth: true,
			smoothTouch: true,
			wheelMultiplier: config.stepSize
				? Math.max(0.1, config.stepSize / 1000)
				: 1,
			touchMultiplier: config.touchMultiplier || 2,
			infinite: config.infiniteScroll === true,
		});

		startAnimationLoop();
		setupScrollEvents(config);
		setupKeyboardSupport(config);
		config.smoothNavigation &&
			setupSmoothScrollNavigation(lenisInstance, getScrollConfig());
		setupResizeHandler();
		isInitialized = true;
		lenisInstance.emit();
	} catch (error) {
		console.error("Lenis initialization error:", error);
		if (retryCount < MAX_RETRIES) {
			retryCount++;
			lenisInstance = null;
			setTimeout(initLenis, 1000);
		}
	}
}

function getScrollConfig() {
	const element = document.querySelector(".tpgb-smooth-scroll");
	let config = {};
	if (element?.dataset.scrollattr) {
		try {
			config = JSON.parse(
				element.dataset.scrollattr.replace(/&quot;/g, '"')
			);
		} catch (e) {
			console.error("Error parsing scroll config:", e);
		}
	}
	return config;
}

function startAnimationLoop() {
	if (!lenisInstance) return;
	function raf(time) {
		if (lenisInstance && !lenisInstance.destroyed) {
			lenisInstance.raf(time);
			requestAnimationFrame(raf);
		}
	}
	requestAnimationFrame(raf);
}

function setupKeyboardSupport(scrollConfig) {
	if (!lenisInstance) return;
	const keyStepSize = scrollConfig.stepSize || 120;
	const pageMultiplier = 5;

	const handleKeydown = (e) => {
		if (
			["INPUT", "TEXTAREA", "SELECT"].includes(e.target.tagName) ||
			e.target.isContentEditable
		)
			return;

		const scrollActions = {
			ArrowDown: keyStepSize,
			ArrowUp: -keyStepSize,
			PageDown: keyStepSize * pageMultiplier,
			PageUp: -keyStepSize * pageMultiplier,
			" ": (e.shiftKey ? -1 : 1) * keyStepSize * pageMultiplier,
		};

		const specialActions = {
			Home: () =>
				performSmoothScroll(
					0,
					scrollConfig.animationTime
						? scrollConfig.animationTime / 2500
						: 1.5
				),
			End: () => {
				const maxScroll = Math.max(
					0,
					document.documentElement.scrollHeight - window.innerHeight
				);
				performSmoothScroll(
					maxScroll,
					scrollConfig.animationTime
						? scrollConfig.animationTime / 2500
						: 1.5
				);
			},
		};

		if (scrollActions[e.key] !== undefined || specialActions[e.key]) {
			e.preventDefault();
			e.stopPropagation();

			if (specialActions[e.key]) {
				specialActions[e.key]();
			} else {
				const currentScroll = lenisInstance.scroll || 0;
				const maxScroll = Math.max(
					0,
					document.documentElement.scrollHeight - window.innerHeight
				);
				const targetScroll = Math.max(
					0,
					Math.min(currentScroll + scrollActions[e.key], maxScroll)
				);

				Math.abs(targetScroll - currentScroll) >= 1 &&
					performSmoothScroll(
						targetScroll,
						scrollConfig.animationTime
							? scrollConfig.animationTime / 5000
							: 0.8
					);
			}
		}
	};

	document.addEventListener("keydown", handleKeydown, {
		capture: true,
		passive: false,
	});
	keyboardCleanup = () =>
		document.removeEventListener("keydown", handleKeydown, {
			capture: true,
		});
}

function performSmoothScroll(targetPosition, duration = 1.2) {
	if (!lenisInstance) return;
	const currentScroll = lenisInstance.scroll || 0;
	const maxScroll = Math.max(
		0,
		document.documentElement.scrollHeight - window.innerHeight
	);
	const clampedTarget = Math.max(0, Math.min(targetPosition, maxScroll));

	Math.abs(clampedTarget - currentScroll) >= 1 &&
		lenisInstance.scrollTo(clampedTarget, { duration });
}

function setupScrollEvents(scrollConfig) {
	if (!lenisInstance) return;

	lenisInstance.on("scroll", (e) => {
		const scrollY = e.scroll || 0;
		const smoothScrollElement = document.querySelector(
			".tpgb-smooth-scroll"
		);

		if (smoothScrollElement?.parentElement) {
			Array.from(smoothScrollElement.parentElement.children).forEach(
				(sibling) => {
					if (
						sibling &&
						sibling !== smoothScrollElement &&
						sibling.getBoundingClientRect().height > 50
					) {
						const r = sibling.getBoundingClientRect();
						const isInViewport =
							r.top < window.innerHeight && r.bottom > 0;
						sibling.classList.toggle("in-viewport", isInViewport);
					}
				}
			);
		}
	});
}

function setupResizeHandler() {
	let resizeTimeout;
	window.addEventListener("resize", () => {
		clearTimeout(resizeTimeout);
		resizeTimeout = setTimeout(() => lenisInstance?.resize(), 250);
	});
}

function destroyScrollSystem() {
	lenisInstance?.destroy();
	lenisInstance = null;
	keyboardCleanup?.();
	keyboardCleanup = null;
	isInitialized = false;
	retryCount = 0;
}

// Public API
window.scrollSystem = {
	init: initializeScrollSystem,
	destroy: destroyScrollSystem,
	reinit: () => {
		destroyScrollSystem();
		setTimeout(initializeScrollSystem, 100);
	},
	getInstance: () => lenisInstance,
};

// Auto-initialize
const initWhenReady = () => {
	if (document.readyState === "loading") {
		document.addEventListener("DOMContentLoaded", initializeScrollSystem);
	} else if (document.readyState === "interactive") {
		setTimeout(initializeScrollSystem, 100);
	} else {
		initializeScrollSystem();
	}
};
initWhenReady();