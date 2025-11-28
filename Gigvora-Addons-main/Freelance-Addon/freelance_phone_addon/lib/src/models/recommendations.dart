import 'gig.dart';
import 'pagination.dart';
import 'project.dart';

class FreelanceRecommendations {
  const FreelanceRecommendations({
    this.gigs = const [],
    this.projects = const [],
    this.pagination,
  });

  final List<Gig> gigs;
  final List<Project> projects;
  final Pagination? pagination;

  factory FreelanceRecommendations.fromJson(Map<String, dynamic>? json) {
    final data = json ?? <String, dynamic>{};
    return FreelanceRecommendations(
      gigs: (data['gigs'] as List?)
              ?.map((item) => Gig.fromJson(item as Map<String, dynamic>?))
              .toList() ??
          const <Gig>[],
      projects: (data['projects'] as List?)
              ?.map((item) => Project.fromJson(item as Map<String, dynamic>?))
              .toList() ??
          const <Project>[],
      pagination: Pagination.fromJson(data['pagination'] as Map<String, dynamic>?),
    );
  }
}
