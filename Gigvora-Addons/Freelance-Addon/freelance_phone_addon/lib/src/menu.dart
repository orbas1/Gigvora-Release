import 'package:flutter/material.dart';
import 'pages/global/freelance_onboarding_screen.dart';
import 'pages/freelancer/freelancer_dashboard_screen.dart';
import 'pages/freelancer/gigs/my_gigs_screen.dart';
import 'pages/freelancer/gigs/gig_edit_screen.dart';
import 'pages/freelancer/gigs/gig_orders_screen.dart';
import 'pages/freelancer/gigs/gig_order_detail_screen.dart';
import 'pages/freelancer/projects/projects_browse_screen.dart';
import 'pages/freelancer/projects/project_detail_screen.dart';
import 'pages/freelancer/projects/proposal_edit_screen.dart';
import 'pages/freelancer/my_proposals_screen.dart';
import 'pages/freelancer/contracts/freelancer_contracts_screen.dart';
import 'pages/shared/contract_detail_screen.dart';
import 'pages/client/client_dashboard_screen.dart';
import 'pages/client/projects/client_projects_screen.dart';
import 'pages/client/projects/client_project_detail_screen.dart';
import 'pages/client/contracts/client_contracts_screen.dart';
import 'pages/shared/escrow_overview_screen.dart';
import 'pages/shared/disputes_list_screen.dart';
import 'pages/shared/dispute_detail_screen.dart';
import 'pages/shared/review_screen.dart';

class MenuItem {
  const MenuItem({required this.title, required this.route, required this.icon});
  final String title;
  final String route;
  final IconData icon;
}

final List<MenuItem> freelancerMenu = const [
  MenuItem(title: 'Freelance Dashboard', route: '/freelance/freelancer/dashboard', icon: Icons.dashboard_outlined),
  MenuItem(title: 'My Gigs', route: '/freelance/freelancer/gigs', icon: Icons.storefront_outlined),
  MenuItem(title: 'Browse Projects', route: '/freelance/freelancer/projects', icon: Icons.search_outlined),
  MenuItem(title: 'My Proposals', route: '/freelance/freelancer/proposals', icon: Icons.assignment_outlined),
  MenuItem(title: 'Contracts', route: '/freelance/freelancer/contracts', icon: Icons.work_outline),
];

final List<MenuItem> clientMenu = const [
  MenuItem(title: 'Client Dashboard', route: '/freelance/client/dashboard', icon: Icons.dashboard_customize_outlined),
  MenuItem(title: 'My Projects', route: '/freelance/client/projects', icon: Icons.assignment_outlined),
  MenuItem(title: 'Contracts', route: '/freelance/client/contracts', icon: Icons.work_history_outlined),
  MenuItem(title: 'Escrow', route: '/freelance/escrow', icon: Icons.account_balance_wallet_outlined),
];

final List<MenuItem> globalMenu = const [
  MenuItem(title: 'Freelance Setup', route: '/freelance/onboarding', icon: Icons.settings_suggest_outlined),
];

List<MenuItem> buildFreelanceMenu({
  required bool isFreelancer,
  required bool isClient,
  bool includeGlobal = true,
}) {
  final items = <MenuItem>[];

  if (includeGlobal) {
    items.addAll(globalMenu);
  }

  if (isFreelancer) {
    items.addAll(freelancerMenu);
  }

  if (isClient) {
    items.addAll(clientMenu);
  }

  return items;
}

Map<String, WidgetBuilder> buildRoutes() => {
      '/freelance/onboarding': (context) => const FreelanceOnboardingScreen(),
      '/freelance/freelancer/dashboard': (context) => const FreelancerDashboardScreen(),
      '/freelance/freelancer/gigs': (context) => const MyGigsScreen(),
      '/freelance/freelancer/gigs/edit': (context) => const GigEditScreen(),
      '/freelance/freelancer/gig-orders': (context) => const GigOrdersScreen(),
      '/freelance/freelancer/gig-order': (context) => const GigOrderDetailScreen(),
      '/freelance/freelancer/projects': (context) => const ProjectsBrowseScreen(),
      '/freelance/freelancer/project': (context) => const ProjectDetailScreen(),
      '/freelance/freelancer/proposal': (context) => const ProposalEditScreen(),
      '/freelance/freelancer/proposals': (context) => const MyProposalsScreen(),
      '/freelance/freelancer/contracts': (context) => const FreelancerContractsScreen(),
      '/freelance/contract': (context) => const ContractDetailScreen(),
      '/freelance/client/dashboard': (context) => const ClientDashboardScreen(),
      '/freelance/client/projects': (context) => const ClientProjectsScreen(),
      '/freelance/client/project': (context) => const ClientProjectDetailScreen(),
      '/freelance/client/contracts': (context) => const ClientContractsScreen(),
      '/freelance/escrow': (context) => const EscrowOverviewScreen(),
      '/freelance/disputes': (context) => const DisputesListScreen(),
      '/freelance/dispute': (context) => const DisputeDetailScreen(),
      '/freelance/review': (context) => const ReviewScreen(),
    };
