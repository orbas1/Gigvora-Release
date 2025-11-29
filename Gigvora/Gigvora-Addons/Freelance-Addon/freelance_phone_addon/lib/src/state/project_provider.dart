import 'package:flutter_riverpod/flutter_riverpod.dart';

import '../models/project.dart';
import '../models/pagination.dart';
import '../repositories/freelance_repository.dart';

class ProjectFilters {
  ProjectFilters({
    this.keyword = '',
    this.location,
    this.minBudget,
    this.maxBudget,
    this.category,
    this.perPage = 20,
  });

  final String keyword;
  final String? location;
  final double? minBudget;
  final double? maxBudget;
  final String? category;
  final int perPage;

  Map<String, dynamic> toQuery() => {
        if (keyword.isNotEmpty) 'keyword': keyword,
        if (location != null && location!.isNotEmpty) 'location': location,
        if (minBudget != null) 'min_price': minBudget,
        if (maxBudget != null) 'max_price': maxBudget,
        if (category != null && category!.isNotEmpty) 'category': category,
        'per_page': perPage,
      };
}

final projectFiltersProvider = StateProvider<ProjectFilters>((ref) => ProjectFilters());

class ProjectsNotifier extends AutoDisposeAsyncNotifier<PagedResult<Project>> {
  @override
  Future<PagedResult<Project>> build() async {
    final repository = ref.watch(freelanceRepositoryProvider);
    final filters = ref.watch(projectFiltersProvider);
    return repository.fetchProjects(filters: filters.toQuery());
  }

  Future<void> refresh() async {
    state = const AsyncLoading();
    state = await AsyncValue.guard(() async {
      final repository = ref.read(freelanceRepositoryProvider);
      final filters = ref.read(projectFiltersProvider);
      return repository.fetchProjects(filters: filters.toQuery());
    });
  }

  Future<void> updateFilters(ProjectFilters filters) async {
    ref.read(projectFiltersProvider.notifier).state = filters;
    await refresh();
  }
}

final projectsProvider = AutoDisposeAsyncNotifierProvider<ProjectsNotifier, PagedResult<Project>>(
  ProjectsNotifier.new,
);

final projectDetailsProvider = FutureProvider.autoDispose.family<Project, String>((ref, slug) {
  final repository = ref.watch(freelanceRepositoryProvider);
  return repository.fetchProjectDetails(slug);
});
