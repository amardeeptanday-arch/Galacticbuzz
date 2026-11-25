/* Galacticbuzz site script. Every block checks its element exists, so one
   file serves every page. */
(function () {
	'use strict';
	const $ = (s, r = document) => r.querySelector(s);
	const $$ = (s, r = document) => Array.from(r.querySelectorAll(s));
	const ico = id => `<svg width="16" height="16" aria-hidden="true"><use href="#i-${id}"/></svg>`;
	const reduce = matchMedia('(prefers-reduced-motion:reduce)').matches;
	const fine = matchMedia('(hover:hover) and (pointer:fine)').matches && !reduce;
	const cfg = window.GB || {};
	const esc = s => String(s).replace(/[&<>"']/g, c => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c]));

	let tt;
	function toast(m) {
		const t = $('#toast');
		if (!t) return;
		t.textContent = m; t.hidden = false;
		clearTimeout(tt); tt = setTimeout(() => (t.hidden = true), 3200);
	}

	/* Mobile menu */
	const mb = $('#menuBtn'), mn = $('#mnav');
	if (mb && mn) {
		mb.addEventListener('click', () => { const o = mn.classList.toggle('open'); mb.setAttribute('aria-expanded', o); });
		mn.addEventListener('click', e => { if (e.target.closest('a')) { mn.classList.remove('open'); mb.setAttribute('aria-expanded', false); } });
	}

	/* Live activity feed: newest item slides in on top */
	const feed = $('#feed');
	if (feed && !reduce) {
		setInterval(() => {
			if (document.hidden) return;
			const last = feed.lastElementChild;
			feed.prepend(last); last.classList.remove('in'); void last.offsetWidth; last.classList.add('in');
			const t = last.querySelector('.t'); if (t) t.textContent = 'now';
		}, 3200);
	}

	/* Hero screenshot: tilted in 3D, straightens as you scroll */
	const tilt = $('#tilt'), tiltWrap = $('#tiltWrap');
	if (tilt && tiltWrap) {
		if (reduce) { tilt.style.transform = 'none'; }
		else {
			let raf = 0;
			const upd = () => {
				raf = 0;
				const r = tiltWrap.getBoundingClientRect(), vh = innerHeight;
				const p = Math.min(1, Math.max(0, (vh - r.top) / (vh * 0.9)));
				tilt.style.transform = `rotateX(${(18 * (1 - p)).toFixed(2)}deg) scale(${(0.96 + 0.04 * p).toFixed(3)})`;
			};
			addEventListener('scroll', () => { if (!raf) raf = requestAnimationFrame(upd); }, { passive: true });
			upd();
		}
	}

	/* Typewriter in the "Real people" card */
	const tw = $('.typing7 .twt');
	if (tw && !reduce) {
		const L = ['Saw your post on the AICPA conference, Mark. Agreed on the staffing point', 'Congrats on the Series A, Priya. Hiring three engineers in a month is a lot', 'Noticed Vista Tax opened a second office in Phoenix. How is staffing going?'];
		let i = 0, k = L[0].length, del = true;
		const tick = () => {
			const s = L[i];
			if (del) { k--; if (k <= 0) { del = false; i = (i + 1) % L.length; } }
			else { k++; if (k >= L[i].length) { del = true; tw.textContent = L[i]; return setTimeout(tick, 2600); } }
			tw.textContent = (del ? s : L[i]).slice(0, k);
			setTimeout(tick, del ? 18 : 38);
		};
		setTimeout(tick, 2600);
	}

	/* Compounding chart phases */
	const phases = $('#phases');
	if (phases) {
		const PHX = [[40, 117], [157, 116], [273, 467]];
		phases.addEventListener('click', e => {
			const b = e.target.closest('button'); if (!b) return;
			$$('#phases button').forEach(x => x.setAttribute('aria-pressed', x === b));
			const [x, w] = PHX[+b.dataset.ph]; const h = $('#phHl');
			if (h) { h.setAttribute('x', x); h.setAttribute('width', w); }
		});
	}

	/* Markets orbit (Who we serve) */
	const orbit = $('#orbit');
	if (orbit) {
		const book = (orbit.dataset.book || '/contact/#book');
		const REG = [
			{ k: 'us', ab: 'US', n: 'United States', s: 'the US', ang: 200, r: 42, hrs: '8am–6pm ET, CT and PT', buy: 'CPA firms, SaaS and mid-market tech, PropTech', ch: 'LinkedIn, email and calling' },
			{ k: 'uk', ab: 'UK', n: 'United Kingdom', s: 'the UK', ang: 270, r: 28, hrs: '9am–5:30pm GMT/BST', buy: 'Accountancy practices, fintech and SaaS scale-ups', ch: 'LinkedIn and email' },
			{ k: 'uae', ab: 'AE', n: 'UAE', s: 'the UAE', ang: 340, r: 42, hrs: '9am–6pm GST, Monday to Friday', buy: 'Family groups, free-zone companies, real estate', ch: 'LinkedIn, email and calling' },
			{ k: 'au', ab: 'AU', n: 'Australia', s: 'Australia', ang: 110, r: 42, hrs: '9am–5pm AEST', buy: 'Accounting firms, SaaS and professional services', ch: 'LinkedIn and email' }
		];
		REG.forEach((g, i) => {
			const a = g.ang * Math.PI / 180;
			orbit.insertAdjacentHTML('beforeend', `<button class="planet" type="button" style="left:${50 + g.r * Math.cos(a)}%;top:${50 + g.r * Math.sin(a)}%" data-reg="${g.k}" aria-pressed="${i === 0}"><i>${g.ab}</i><span>${g.n}</span></button>`);
		});
		const card = $('#regionCard');
		const showReg = k => {
			const g = REG.find(r => r.k === k);
			$$('.planet').forEach(p => p.setAttribute('aria-pressed', p.dataset.reg === k));
			if (card) card.innerHTML = `<h3 style="font-size:1.6rem">${g.n}</h3><dl><dt>Our hours</dt><dd>${g.hrs}</dd><dt>Typical buyers</dt><dd>${g.buy}</dd><dt>Channels</dt><dd>${g.ch}</dd></dl><a class="btn btn-primary btn-sm" href="${esc(book)}" style="justify-self:start">Plan outreach in ${g.s}</a>`;
		};
		orbit.addEventListener('click', e => { const p = e.target.closest('.planet'); if (p) showReg(p.dataset.reg); });
		showReg('us');
	}

	/* Light that follows the cursor on cards, plus team-card tilt */
	document.addEventListener('pointermove', e => {
		const s = e.target.closest && e.target.closest('.spot,.tcard'); if (!s) return;
		const r = s.getBoundingClientRect();
		s.style.setProperty('--mx', (e.clientX - r.left) + 'px');
		s.style.setProperty('--my', (e.clientY - r.top) + 'px');
		if (fine && s.classList.contains('tcard')) {
			const x = (e.clientX - r.left) / r.width - 0.5, y = (e.clientY - r.top) / r.height - 0.5;
			s.style.transform = `perspective(900px) rotateX(${(-y * 8).toFixed(2)}deg) rotateY(${(x * 8).toFixed(2)}deg)`;
		}
	});
	document.addEventListener('pointerout', e => {
		const s = e.target.closest && e.target.closest('.tcard');
		if (s && !s.contains(e.relatedTarget)) s.style.transform = '';
	});

	/* Team: "View profile" bubble and profile panel */
	const teamGrid = $('#teamGrid');
	if (teamGrid) {
		const dot = $('#cursorDot');
		if (fine && dot) {
			let tx = -200, ty = -200, cx = -200, cy = -200, run = false;
			const loop = () => {
				cx += (tx - cx) * 0.18; cy += (ty - cy) * 0.18;
				dot.style.transform = `translate3d(${cx}px,${cy}px,0)`;
				if (Math.abs(tx - cx) > 0.3 || Math.abs(ty - cy) > 0.3) requestAnimationFrame(loop); else run = false;
			};
			teamGrid.addEventListener('pointermove', e => {
				tx = e.clientX; ty = e.clientY;
				dot.classList.toggle('on', !!e.target.closest('.tcard'));
				if (!run) { run = true; requestAnimationFrame(loop); }
			});
			teamGrid.addEventListener('pointerleave', () => dot.classList.remove('on'));
		}
		teamGrid.addEventListener('click', e => {
			const c = e.target.closest('.tcard'); if (!c) return;
			const d = c.dataset;
			const pic = d.photo ? `<img src="${esc(d.photo)}" alt="${esc(d.name)}" style="width:100%;height:100%;object-fit:cover">` : c.querySelector('.photo').innerHTML;
			const ask = (d.ask || '').split('|').filter(Boolean);
			const body = $('#profileBody'); if (!body) return;
			body.innerHTML = `<div class="profile"><div class="pp">${pic}</div><div style="display:grid;gap:12px">${d.role ? `<span class="eyebrow">${esc(d.role)}</span>` : ''}<h3 id="pmName" style="font-size:1.7rem">${esc(d.name)}</h3>${d.bio ? `<p class="muted">${esc(d.bio)}</p>` : ''}${ask.length ? `<div><span class="xs mono muted">ASK ME ABOUT</span><div class="regions" style="margin-top:6px">${ask.map(a => `<span><i></i>${esc(a)}</span>`).join('')}</div></div>` : ''}${d.tz ? `<div><span class="xs mono muted">TIME ZONE</span><div>${esc(d.tz)}</div></div>` : ''}<div style="display:flex;gap:10px;flex-wrap:wrap">${d.li ? `<a class="btn btn-primary btn-sm" href="${esc(d.li)}" target="_blank" rel="noopener">LinkedIn</a>` : ''}<a class="btn btn-ghost btn-sm" href="${esc(teamGrid.dataset.book || '/contact/#book')}">Book a call</a></div></div></div>`;
			$('#profileModal').hidden = false;
		});
	}

	/* Modals */
	document.addEventListener('click', e => {
		if (e.target.closest('[data-close]') || e.target.classList.contains('modal')) $$('.modal').forEach(m => (m.hidden = true));
	});
	document.addEventListener('keydown', e => { if (e.key === 'Escape') $$('.modal').forEach(m => (m.hidden = true)); });

	/* Forms: validation, collecting answers, sending */
	function validate(scope, errEl) {
		let ok = true;
		$$('[data-req]', scope).forEach(i => {
			if (i.closest('[hidden]')) return;
			const bad = !i.value.trim() || (i.type === 'email' && !/^\S+@\S+\.\S+$/.test(i.value));
			i.style.borderColor = bad ? 'var(--reject)' : '';
			if (bad) ok = false;
		});
		if (errEl) { errEl.hidden = ok; errEl.textContent = ok ? '' : 'Fill in the highlighted fields.'; }
		return ok;
	}
	const BUD = ['Under $1K', '$1–2K', '$2–4K', '$4–8K', '$8–20K', '$20K+'];
	function collect(form) {
		const out = [], groups = {};
		$$('input,select,textarea', form).forEach(el => {
			if (['button', 'submit', 'hidden'].includes(el.type) || el.name === 'gb_hp' || el.closest('[hidden]')) return;
			const field = el.closest('.field');
			const glabel = field && field.querySelector('.lbl') ? field.querySelector('.lbl').textContent.trim() : '';
			const own = el.labels && el.labels[0] ? el.labels[0].textContent.trim() : (el.getAttribute('aria-label') || el.name || el.id);
			if (el.type === 'checkbox' || el.type === 'radio') {
				if (!el.checked) return;
				if (el.id === 'm-consent') { out.push(['Consent to share brief with shortlist', 'Yes']); return; }
				const NAMES = { cat: 'Need', when: 'Start', topic: 'Topic' };
				const k = glabel || NAMES[el.name] || (field ? el.name || own : own);
				const v = el.type === 'radio' ? (el.value && el.value !== 'on' ? own || el.value : own) : (!glabel && !field ? 'Yes' : (el.value && el.value !== 'on' ? el.value : own));
				(groups[k] = groups[k] || []).push(v);
				return;
			}
			if (el.type === 'range' && el.id === 'm-budget') { out.push(['Monthly budget', BUD[el.value]]); return; }
			const v = (el.value || '').trim();
			if (v) out.push([own, v]);
		});
		Object.keys(groups).forEach(k => out.push([k, groups[k].join(', ')]));
		return out;
	}
	function send(form, type, extra) {
		const fields = collect(form).concat(extra || []);
		fields.push(['Page', location.href]);
		const fd = new FormData();
		fd.append('action', 'gb_lead');
		fd.append('nonce', cfg.nonce || '');
		fd.append('form', type);
		fd.append('fields', JSON.stringify(fields));
		const hp = form.querySelector('[name=gb_hp]'); fd.append('gb_hp', hp ? hp.value : '');
		return fetch(cfg.ajax || '/wp-admin/admin-ajax.php', { method: 'POST', body: fd, credentials: 'same-origin' })
			.then(r => r.json().catch(() => ({ success: false })).then(j => {
				if (!r.ok || !j.success) throw new Error((j.data && j.data.message) || 'Something went wrong. Please email us instead.');
				return j;
			}));
	}
	function busy(btn, on, label) {
		if (!btn) return;
		if (on) { btn.dataset.html = btn.innerHTML; btn.disabled = true; btn.textContent = label || 'Sending…'; }
		else { btn.disabled = false; if (btn.dataset.html) btn.innerHTML = btn.dataset.html; }
	}

	/* Contact: preferred time picker (shown when no booking link is set) */
	let slot = '';
	const cDays = $('#cDays'), cSlots = $('#cSlots');
	if (cDays && cSlots) {
		const T = ['9:00 am', '10:00 am', '11:30 am', '1:00 pm', '3:00 pm', '4:30 pm'];
		const out = []; let x = new Date();
		while (out.length < 5) { x = new Date(x.getTime() + 864e5); const w = x.getDay(); if (w && w < 6) out.push(new Date(x)); }
		let day = out[0];
		cDays.innerHTML = out.map((x, i) => `<button type="button" data-i="${i}" aria-pressed="${i === 0}"><small>${x.toLocaleDateString('en-US', { weekday: 'short' })}</small><b>${x.getDate()}</b><small>${x.toLocaleDateString('en-US', { month: 'short' })}</small></button>`).join('');
		const draw = () => { cSlots.innerHTML = T.map(t => `<button type="button" aria-pressed="false">${t}</button>`).join(''); slot = ''; };
		draw();
		cDays.addEventListener('click', e => { const b = e.target.closest('button'); if (!b) return; $$('button', cDays).forEach(y => y.setAttribute('aria-pressed', y === b)); day = out[+b.dataset.i]; draw(); });
		cSlots.addEventListener('click', e => { const b = e.target.closest('button'); if (!b) return; $$('button', cSlots).forEach(y => y.setAttribute('aria-pressed', y === b)); slot = day.toLocaleDateString('en-US', { weekday: 'long', month: 'short', day: 'numeric' }) + ' at ' + b.textContent; });
	}
	const callForm = $('#callForm');
	if (callForm) {
		callForm.addEventListener('submit', e => {
			e.preventDefault();
			const er = $('.err', callForm);
			if (!validate(callForm, er)) return;
			if (cDays && !slot) { er.hidden = false; er.textContent = 'Pick a preferred time on the left.'; return; }
			const tz = (Intl.DateTimeFormat().resolvedOptions().timeZone || '');
			const btn = callForm.querySelector('[type=submit]'); busy(btn, true);
			send(callForm, 'call', slot ? [['Preferred time', slot + (tz ? ' (' + tz + ')' : '')]] : [])
				.then(() => { const s = $('#callSlot'); if (s && slot) s.textContent = slot; callForm.hidden = true; $('#callDone').hidden = false; })
				.catch(err => { er.hidden = false; er.textContent = err.message; })
				.finally(() => busy(btn, false));
		});
	}
	const msgForm = $('#msgForm');
	if (msgForm) {
		msgForm.addEventListener('submit', e => {
			e.preventDefault();
			const er = $('.err', msgForm);
			if (!validate(msgForm, er)) return;
			const btn = msgForm.querySelector('[type=submit]'); busy(btn, true);
			send(msgForm, 'message')
				.then(() => { msgForm.hidden = true; $('#msgDone').hidden = false; })
				.catch(err => { er.hidden = false; er.textContent = err.message; })
				.finally(() => busy(btn, false));
		});
		const again = $('#msgAgain');
		if (again) again.addEventListener('click', () => { msgForm.reset(); msgForm.hidden = false; $('#msgDone').hidden = true; });
	}
	const copy = $('#copyEmail');
	if (copy) {
		copy.addEventListener('click', () => {
			const t = copy.dataset.email || cfg.email || '';
			(navigator.clipboard ? navigator.clipboard.writeText(t) : Promise.reject())
				.then(() => toast('Email address copied'), () => toast('Select the address to copy it'));
		});
	}

	/* Find a partner: 3-step brief */
	const mw = $('#matchWiz');
	if (mw) {
		const val = n => { const c = $(`[name="${n}"]:checked`, mw); return c ? c.value : ''; };
		const vals = n => $$(`[name="${n}"]:checked`, mw).filter(c => !c.closest('[hidden]')).map(c => c.value);
		const syncCond = () => $$('[data-show-if]', mw).forEach(el => { const [k, v] = el.dataset.showIf.split('='); el.hidden = val(k) !== v; });
		const sum = () => {
			const set = (id, t, e) => { const el = $('#' + id); if (!el) return; el.textContent = t || e || '—'; el.classList.toggle('empty', !t); };
			const c = $('[name=cat]:checked', mw);
			set('sum-cat', c ? c.dataset.sum : '', 'Not chosen yet');
			set('sum-svc', vals('svc').join(', '));
			set('sum-det', [$('#m-tools').value, $('#m-country').value, BUD[$('#m-budget').value] + '/mo', val('when')].filter(Boolean).join(' · '));
			set('sum-co', [$('#m-co').value, $('#m-name').value].filter(Boolean).join(' · '));
		};
		['input', 'change'].forEach(ev => mw.addEventListener(ev, () => { syncCond(); sum(); $('#m-budget-v').textContent = BUD[$('#m-budget').value]; }));
		const steps = $$('.wstep', mw), bars = $$('.wiz-steps i', mw), labels = $$('.wiz-labels span', mw), err = $('.err', mw), back = $('[data-back]', mw), next = $('[data-next]', mw);
		let cur = 0;
		const hints = ['Step 1 of 3', 'Rough answers are fine', 'Only your shortlist sees this'];
		const show = i => {
			cur = i;
			steps.forEach((s, j) => s.classList.toggle('on', j === i));
			bars.forEach((b, j) => b.classList.toggle('done', j <= i));
			labels.forEach((l, j) => l.classList.toggle('cur', j === i));
			back.style.visibility = i ? 'visible' : 'hidden';
			next.innerHTML = (i === steps.length - 1 ? 'Send brief ' : 'Continue ') + ico('arrow');
			err.hidden = true;
			const h = $('#wizHint'); if (h) h.textContent = hints[i] || '';
		};
		const check = i => {
			if (i === 0 && !val('cat')) return 'Choose what you need help with.';
			if (i === steps.length - 1 && $('#m-consent') && !$('#m-consent').checked) return 'Tick the box so we can share your brief with your shortlist.';
			return '';
		};
		back.addEventListener('click', () => show(Math.max(0, cur - 1)));
		next.addEventListener('click', () => {
			const x = check(cur);
			if (!validate(steps[cur], err) || x) { if (x) { err.hidden = false; err.textContent = x; } return; }
			if (cur < steps.length - 1) { show(cur + 1); return; }
			busy(next, true);
			send(mw, 'brief')
				.then(() => {
					const n = $('#m-name').value.trim().split(' ')[0];
					$('#ty-name').textContent = n ? ', ' + n : '';
					$('#ty-email').textContent = $('#m-email').value;
					mw.hidden = true; $('#briefDone').hidden = false;
					$('#brief').scrollIntoView({ block: 'start' });
				})
				.catch(e2 => { err.hidden = false; err.textContent = e2.message; })
				.finally(() => { busy(next, false); next.innerHTML = 'Send brief ' + ico('arrow'); });
		});
		const again = $('#briefAgain');
		if (again) again.addEventListener('click', () => { mw.reset(); syncCond(); sum(); show(0); mw.hidden = false; $('#briefDone').hidden = true; });
		syncCond(); sum(); show(0);

		const SVCMAP = { 'bookkeeping': 'Bookkeeping', 'tax preparation': 'Tax preparation', 'payroll': 'Payroll', 'training data': 'Training data', 'data labelling': 'Data labelling', 'B2B data enrichment': 'B2B data enrichment', 'an AI agent for back-office work': 'AI agent for back-office work', 'workflow automation': 'Workflow automation', 'a SaaS product build': 'SaaS product build', 'QA & testing': 'QA & testing', 'cloud & DevOps': 'Cloud & DevOps' };
		const CMAP = { 'the USA': 'USA', 'the UK': 'UK', 'Canada': 'Canada', 'Australia': 'Australia', 'the UAE': 'UAE', 'another country': 'Other' };
		const hb = $('#heroBrief');
		if (hb) {
			hb.addEventListener('submit', e => {
				e.preventDefault();
				const o = $('#hb-svc').selectedOptions[0];
				const r = $(`[name=cat][value="${o.dataset.cat}"]`, mw); if (r) r.checked = true;
				$$('[name=svc]', mw).forEach(c => (c.checked = false)); syncCond();
				const c = $$('[name=svc]', mw).find(x => x.value === SVCMAP[o.text] && !x.closest('[hidden]')); if (c) c.checked = true;
				const cc = CMAP[$('#hb-country').value]; if (cc) $('#m-country').value = cc;
				sum(); show(1);
				$('#brief').scrollIntoView({ behavior: reduce ? 'auto' : 'smooth', block: 'start' });
				toast('Brief started with your answers.');
			});
		}
	}

	/* Sentence-style selects size to the chosen word */
	const cv = document.createElement('canvas').getContext('2d');
	const fit = sel => {
		const cs = getComputedStyle(sel);
		cv.font = cs.fontWeight + ' ' + cs.fontSize + ' ' + cs.fontFamily;
		const t = sel.selectedOptions[0] ? sel.selectedOptions[0].text : '';
		const w = cv.measureText(t).width; if (w) sel.style.width = Math.ceil(w + 38) + 'px';
	};
	const fitAll = () => $$('.sentence select').forEach(fit);
	document.addEventListener('change', e => { if (e.target.matches && e.target.matches('.sentence select')) fit(e.target); });
	fitAll();
	if (document.fonts && document.fonts.ready) document.fonts.ready.then(fitAll);
})();
