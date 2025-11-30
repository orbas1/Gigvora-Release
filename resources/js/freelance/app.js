import './modules/freelancerDashboard';
import './modules/freelanceOnboarding';
import './modules/adminFreelanceDashboard';
import './modules/contractDetail';
import './modules/disputeCentre';
import './modules/feesPreview';
import './modules/gigsIndex';
import './modules/gigWizard';
import './modules/orderDetail';
import './modules/ordersList';
import './modules/projectBrowse';
import './modules/projectDetail';
import './modules/projectProposals';
import './modules/projectWizard';
import './modules/proposalForm';
import './modules/proposalsList';
import './modules/utils';

document.addEventListener('DOMContentLoaded', () => {
    const accordions = document.querySelectorAll('[data-freelance-accordion]');

    accordions.forEach((button) => {
        button.addEventListener('click', () => {
            const targetId = button.getAttribute('aria-controls');
            const target = document.getElementById(targetId);

            if (!target) {
                return;
            }

            const expanded = button.getAttribute('aria-expanded') === 'true';
            button.setAttribute('aria-expanded', (!expanded).toString());
            target.classList.toggle('hidden', expanded);
        });
    });
});
