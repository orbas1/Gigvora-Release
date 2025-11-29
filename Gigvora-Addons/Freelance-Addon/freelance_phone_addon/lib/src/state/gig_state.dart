import 'package:flutter_riverpod/flutter_riverpod.dart';
import '../models/gig.dart';
import '../services/gig_service.dart';

class GigListNotifier extends StateNotifier<AsyncValue<List<Gig>>> {
  GigListNotifier(this.service) : super(const AsyncValue.loading());
  final GigService service;

  Future<void> load({String? status}) async {
    state = const AsyncValue.loading();
    try {
      final gigs = await service.fetchMyGigs(status: status);
      state = AsyncValue.data(gigs);
    } catch (e, st) {
      state = AsyncValue.error(e, st);
    }
  }
}
