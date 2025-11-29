import 'gig.dart';
import 'pagination.dart';
import 'project.dart';
import 'tag.dart';

class FreelanceSearchResult {
  const FreelanceSearchResult({
    this.gigs = const [],
    this.projects = const [],
    this.tags = const [],
    this.pagination,
  });

  final List<Gig> gigs;
  final List<Project> projects;
  final List<FreelanceTag> tags;
  final Pagination? pagination;

  factory FreelanceSearchResult.fromJson(Map<String, dynamic>? json) {
    final data = json ?? <String, dynamic>{};
    return FreelanceSearchResult(
      gigs: (data['gigs'] as List?)
              ?.map((item) => Gig.fromJson(item as Map<String, dynamic>?))
              .toList() ??
          const <Gig>[],
      projects: (data['projects'] as List?)
              ?.map((item) => Project.fromJson(item as Map<String, dynamic>?))
              .toList() ??
          const <Project>[],
      tags: (data['tags'] as List?)
              ?.map((item) => FreelanceTag.fromJson(item as Map<String, dynamic>?))
              .toList() ??
          const <FreelanceTag>[],
      pagination: Pagination.fromJson(data['pagination'] as Map<String, dynamic>?),
    );
  }
}
