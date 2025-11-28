import 'package:flutter_riverpod/flutter_riverpod.dart';

import '../models/gig.dart';
import '../models/pagination.dart';
import '../repositories/freelance_repository.dart';
import 'core_providers.dart';

class GigFilters {
  GigFilters({
    this.keyword = '',
    this.minPrice,
    this.maxPrice,
    this.location,
    this.category,
    this.perPage = 20,
    this.sortBy,
  });

  final String keyword;
  final double? minPrice;
  final double? maxPrice;
  final String? location;
  final String? category;
  final int perPage;
  final String? sortBy;

  Map<String, dynamic> toQuery() => {
        if (keyword.isNotEmpty) 'keyword': keyword,
        if (minPrice != null) 'min_price': minPrice,
        if (maxPrice != null) 'max_price': maxPrice,
        if (location != null && location!.isNotEmpty) 'location': location,
        if (category != null && category!.isNotEmpty) 'category': category,
        'per_page': perPage,
        if (sortBy != null && sortBy!.isNotEmpty) 'sort_by': sortBy,
      };
}

final gigFiltersProvider = StateProvider<GigFilters>((ref) => GigFilters());

class GigsNotifier extends AutoDisposeAsyncNotifier<PagedResult<Gig>> {
  @override
  Future<PagedResult<Gig>> build() async {
    final repository = ref.watch(freelanceRepositoryProvider);
    final filters = ref.watch(gigFiltersProvider);
    return repository.fetchGigs(filters: filters.toQuery());
  }

  Future<void> refresh() async {
    state = const AsyncLoading();
    state = await AsyncValue.guard(() async {
      final repository = ref.read(freelanceRepositoryProvider);
      final filters = ref.read(gigFiltersProvider);
      return repository.fetchGigs(filters: filters.toQuery());
    });
  }

  Future<void> updateFilters(GigFilters filters) async {
    ref.read(gigFiltersProvider.notifier).state = filters;
    await refresh();
  }
}

final gigsProvider = AutoDisposeAsyncNotifierProvider<GigsNotifier, PagedResult<Gig>>(
  GigsNotifier.new,
);

final gigDetailsProvider = FutureProvider.autoDispose.family<Gig, int>((ref, id) {
  final repository = ref.watch(freelanceRepositoryProvider);
  return repository.fetchGigDetails(id);
});
