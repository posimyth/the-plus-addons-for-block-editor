console.log("hello");
function setupSmoothScrollNavigation(lenisInstance, scrollConfig = {}) {
	if (!lenisInstance) return;

	document.addEventListener(
		"click",
		function (e) {
			const target = e.target.closest(".tpgb-scroll-nav-item");
			if (!target) return;

			const scrollTarget =
				target.dataset.scrollTo ||
				(target.getAttribute("href")?.startsWith("#") &&
					target.getAttribute("href")) ||
				target.dataset.target;

			if (!scrollTarget) return;

			e.preventDefault();
			e.stopPropagation();

			const targetPosition = calculateScrollPosition(scrollTarget, 0);
			if (targetPosition !== null) {
				performSmoothScroll(
					targetPosition,
					scrollConfig.animationTime
						? Math.max(0.1, scrollConfig.animationTime / 2500)
						: 1.2
				);
			}
		},
		true
	);

	function calculateScrollPosition(target, offset = 0) {
		if (typeof target === "number") return target + offset;

		if (typeof target === "string") {
			if (target === "top" || target === "#top") return 0 + offset;

			if (target === "bottom" || target === "#bottom") {
				return (
					Math.max(
						0,
						document.documentElement.scrollHeight -
							window.innerHeight
					) + offset
				);
			}

			const el = document.querySelector(target);
			if (el) {
				const rect = el.getBoundingClientRect();
				const currentScroll = lenisInstance?.scroll || 0;
				return rect.top + currentScroll + offset;
			}
		}
		return null;
	}

	function performSmoothScroll(position, duration = 1.2) {
		if (!lenisInstance) return;

		const currentScroll = lenisInstance.scroll || 0;
		const maxScroll = Math.max(
			0,
			document.documentElement.scrollHeight - window.innerHeight
		);
		const clamped = Math.max(0, Math.min(position, maxScroll));

		if (Math.abs(clamped - currentScroll) >= 1) {
			lenisInstance.scrollTo(clamped, { duration });
		}
	}
}
