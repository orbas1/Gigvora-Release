import 'package:flutter_riverpod/flutter_riverpod.dart';

import '../models/escrow.dart';
import '../models/project.dart';
import '../repositories/freelance_repository.dart';

class DashboardSnapshot {
  DashboardSnapshot({
    required this.gigCount,
    required this.projectCount,
    required this.disputeCount,
    required this.escrows,
    required this.recommendedProjects,
  });

  final int gigCount;
  final int projectCount;
  final int disputeCount;
  final List<Escrow> escrows;
  final List<Project> recommendedProjects;
}

final dashboardSnapshotProvider = FutureProvider.autoDispose<DashboardSnapshot>((ref) async {
  final repository = ref.watch(freelanceRepositoryProvider);

  final gigs = await repository.fetchGigs(filters: {'per_page': 5});
  final projects = await repository.fetchProjects(filters: {'per_page': 5});
  final disputes = await repository.fetchDisputes();
  final escrows = await repository.fetchEscrows();
  final recommendations = await repository.fetchRecommendations(limit: 5);

  return DashboardSnapshot(
    gigCount: gigs.pagination.total,
    projectCount: projects.pagination.total,
    disputeCount: disputes.length,
    escrows: escrows,
    recommendedProjects: recommendations.projects,
  );
});
