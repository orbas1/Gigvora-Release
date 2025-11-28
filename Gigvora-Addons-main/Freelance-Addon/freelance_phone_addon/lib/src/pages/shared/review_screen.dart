import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';

import '../../repositories/freelance_repository.dart';

class ReviewScreen extends ConsumerStatefulWidget {
  const ReviewScreen({super.key});

  @override
  ConsumerState<ReviewScreen> createState() => _ReviewScreenState();
}

class _ReviewScreenState extends ConsumerState<ReviewScreen> {
  int rating = 0;
  final _headline = TextEditingController();
  final _comment = TextEditingController();
  bool submitting = false;

  @override
  Widget build(BuildContext context) {
    final int? userId = ModalRoute.of(context)?.settings.arguments as int?;
    return Scaffold(
      appBar: AppBar(title: const Text('Leave a Review')),
      body: Padding(
        padding: const EdgeInsets.all(16),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Row(
              children: List.generate(5, (i) {
                final idx = i + 1;
                return IconButton(
                  icon: Icon(idx <= rating ? Icons.star : Icons.star_border, color: Colors.amber),
                  onPressed: () => setState(() => rating = idx),
                );
              }),
            ),
            TextField(controller: _headline, decoration: const InputDecoration(labelText: 'Headline')),
            TextField(controller: _comment, maxLines: 4, decoration: const InputDecoration(labelText: 'Comment')),
            const Spacer(),
            ElevatedButton(
              onPressed: submitting || userId == null ? null : () => _submit(userId!),
              child: submitting ? const CircularProgressIndicator() : const Text('Submit'),
            )
          ],
        ),
      ),
    );
  }

  Future<void> _submit(int userId) async {
    setState(() => submitting = true);
    try {
      final repo = ref.read(freelanceRepositoryProvider);
      await repo.submitProfileReview(
        userId: userId,
        rating: rating.toDouble(),
        comment: _comment.text.isEmpty ? null : _comment.text,
        reference: _headline.text.isEmpty ? null : _headline.text,
      );
      if (mounted) {
        Navigator.pop(context);
        ScaffoldMessenger.of(context).showSnackBar(
          const SnackBar(content: Text('Review submitted successfully')),
        );
      }
    } catch (error) {
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(content: Text('Unable to submit review: $error')),
      );
    } finally {
      if (mounted) setState(() => submitting = false);
    }
  }
}
