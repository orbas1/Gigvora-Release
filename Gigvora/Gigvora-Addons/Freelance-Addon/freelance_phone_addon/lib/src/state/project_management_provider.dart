import 'package:flutter_riverpod/flutter_riverpod.dart';

import '../models/project_board.dart';
import '../repositories/freelance_repository.dart';

final projectBoardProvider = FutureProvider.autoDispose.family<ProjectBoard, String>((ref, slug) {
  final repository = ref.watch(freelanceRepositoryProvider);
  return repository.fetchProjectBoard(slug);
});
