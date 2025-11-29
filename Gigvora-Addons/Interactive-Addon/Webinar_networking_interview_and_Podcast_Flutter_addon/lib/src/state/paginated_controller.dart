import 'package:flutter/foundation.dart';

import '../models/pagination.dart';

class PaginatedController<T> extends ChangeNotifier {
  final Future<PaginatedResponse<T>> Function(int page) loader;

  PaginatedController(this.loader);

  final List<T> items = [];
  bool isLoading = false;
  bool hasMore = true;
  String? error;
  int _page = 1;

  Future<void> loadInitial() async {
    items.clear();
    hasMore = true;
    _page = 1;
    await loadMore(resetPage: true);
  }

  Future<void> loadMore({bool resetPage = false}) async {
    if (isLoading || !hasMore) return;
    isLoading = true;
    error = null;
    notifyListeners();

    try {
      if (resetPage) {
        _page = 1;
      }
      final response = await loader(_page);
      if (resetPage) {
        items.clear();
      }
      items.addAll(response.data);
      hasMore = response.currentPage < response.lastPage;
      _page += 1;
    } catch (e) {
      error = e.toString();
    } finally {
      isLoading = false;
      notifyListeners();
    }
  }
}
