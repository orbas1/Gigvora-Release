import 'escrow.dart';
import 'project.dart';

class WorkspaceSnapshot {
  final FreelancerWorkspace? freelancer;
  final ClientWorkspace? client;

  const WorkspaceSnapshot({
    this.freelancer,
    this.client,
  });

  factory WorkspaceSnapshot.fromJson(Map<String, dynamic>? json) {
    return WorkspaceSnapshot(
      freelancer: json?['freelancer'] != null
          ? FreelancerWorkspace.fromJson(json!['freelancer'] as Map<String, dynamic>)
          : null,
      client: json?['client'] != null
          ? ClientWorkspace.fromJson(json!['client'] as Map<String, dynamic>)
          : null,
    );
  }
}

class FreelancerWorkspace {
  final int activeGigs;
  final int openContracts;
  final int openProposals;
  final int openDisputes;
  final String earningsMonth;
  final List<Escrow> escrows;
  final List<Project> recommendations;

  const FreelancerWorkspace({
    required this.activeGigs,
    required this.openContracts,
    required this.openProposals,
    required this.openDisputes,
    required this.earningsMonth,
    required this.escrows,
    required this.recommendations,
  });

  factory FreelancerWorkspace.fromJson(Map<String, dynamic> json) {
    final metrics = json['metrics'] as Map<String, dynamic>? ?? const {};
    return FreelancerWorkspace(
      activeGigs: metrics['active_gigs'] as int? ?? 0,
      openContracts: metrics['open_contracts'] as int? ?? 0,
      openProposals: metrics['open_proposals'] as int? ?? 0,
      openDisputes: metrics['open_disputes'] as int? ?? 0,
      earningsMonth: metrics['earnings_month'] as String? ?? '',
      escrows: (json['escrow'] as List?)
              ?.map((item) => Escrow.fromJson(item as Map<String, dynamic>))
              .toList() ??
          const <Escrow>[],
      recommendations: (json['recommendations'] as List?)
              ?.map((item) => Project.fromJson(_mapRecommendation(item)))
              .toList() ??
          const <Project>[],
    );
  }

  static Map<String, dynamic> _mapRecommendation(dynamic value) {
    final data = value as Map<String, dynamic>? ?? const {};
    return {
      'id': data['id'] ?? 0,
      'slug': data['link'],
      'title': data['title'] ?? '',
      'description': data['description'] ?? data['summary'] ?? '',
      'budget': _budgetToDouble(data['budget'] ?? data['price']),
      'type': data['type'] ?? 'fixed',
      'proposals_count': data['proposals_count'] ?? 0,
      'client_name': data['owner'] ?? '',
    };
  }

  static double _budgetToDouble(dynamic value) {
    if (value is num) return value.toDouble();
    if (value is String) {
      final parsed = double.tryParse(value.replaceAll(RegExp('[^0-9.]'), ''));
      return parsed ?? 0;
    }
    return 0;
  }
}

class ClientWorkspace {
  final int openProjects;
  final int activeContracts;
  final int openDisputes;
  final String escrowVolume;

  const ClientWorkspace({
    required this.openProjects,
    required this.activeContracts,
    required this.openDisputes,
    required this.escrowVolume,
  });

  factory ClientWorkspace.fromJson(Map<String, dynamic> json) {
    final metrics = json['metrics'] as Map<String, dynamic>? ?? const {};
    return ClientWorkspace(
      openProjects: metrics['open_projects'] as int? ?? 0,
      activeContracts: metrics['active_contracts'] as int? ?? 0,
      openDisputes: metrics['open_disputes'] as int? ?? 0,
      escrowVolume: metrics['escrow_volume'] as String? ?? '',
    );
  }
}

