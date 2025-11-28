import 'package:flutter_riverpod/flutter_riverpod.dart';
import '../models/dispute.dart';
import '../services/dispute_service.dart';

class DisputeNotifier extends StateNotifier<AsyncValue<List<Dispute>>> {
  DisputeNotifier(this.service) : super(const AsyncValue.loading());
  final DisputeService service;

  Future<void> load() async {
    state = const AsyncValue.loading();
    try {
      state = AsyncValue.data(await service.listDisputes());
    } catch (e, st) {
      state = AsyncValue.error(e, st);
    }
  }
}

class DisputeDetailNotifier extends StateNotifier<AsyncValue<Dispute?>> {
  DisputeDetailNotifier(this.service) : super(const AsyncValue.loading());
  final DisputeService service;

  Future<void> fetch(int id) async {
    state = const AsyncValue.loading();
    try {
      state = AsyncValue.data(await service.fetchDispute(id));
    } catch (e, st) {
      state = AsyncValue.error(e, st);
    }
  }

  Future<void> message(int id, String body) => service.postMessage(id, body);
}
