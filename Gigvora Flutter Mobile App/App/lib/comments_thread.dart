import 'package:flutter/material.dart';

import 'comments_api.dart';

/// Live-service comment thread that mirrors the Laravel feed comment
/// experience. Fetches, posts, reacts, and deletes against
/// `/api/get_comment/{postId}`, `/api/post_comment`, and
/// `/api/comment_delete/{comment_id}` without any stubbed data.
class GigvoraCommentThread extends StatefulWidget {
  const GigvoraCommentThread({
    super.key,
    required this.client,
    required this.postId,
    this.postType = 'post',
    this.enableDeletion = true,
    this.padding = const EdgeInsets.all(16),
    this.controller,
  });

  final GigvoraCommentsClient client;
  final int postId;
  final String postType;
  final bool enableDeletion;
  final EdgeInsetsGeometry padding;
  final ScrollController? controller;

  @override
  State<GigvoraCommentThread> createState() => _GigvoraCommentThreadState();
}

class _GigvoraCommentThreadState extends State<GigvoraCommentThread> {
  final TextEditingController _composer = TextEditingController();
  bool _loading = true;
  bool _posting = false;
  String? _error;
  List<GigvoraComment> _comments = const [];
  int? _replyTo;

  @override
  void initState() {
    super.initState();
    _load();
  }

  @override
  void dispose() {
    _composer.dispose();
    super.dispose();
  }

  Future<void> _load() async {
    setState(() {
      _loading = true;
      _error = null;
    });

    try {
      final comments = await widget.client.fetchThread(postId: widget.postId);
      if (!mounted) return;
      setState(() => _comments = comments);
    } catch (e) {
      setState(() => _error = e.toString());
    } finally {
      if (mounted) {
        setState(() => _loading = false);
      }
    }
  }

  Future<void> _postComment() async {
    final text = _composer.text.trim();
    if (text.isEmpty) return;

    setState(() => _posting = true);
    try {
      await widget.client.createComment(
        postId: widget.postId,
        body: text,
        parentId: _replyTo ?? 0,
        postType: widget.postType,
      );
      if (!mounted) return;
      _composer.clear();
      setState(() => _replyTo = null);
      await _load();
    } catch (e) {
      if (mounted) {
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(content: Text('Unable to post comment: $e')),
        );
      }
    } finally {
      if (mounted) {
        setState(() => _posting = false);
      }
    }
  }

  Future<void> _react(int commentId, String reaction) async {
    try {
      await widget.client.reactToComment(commentId: commentId, reaction: reaction);
      await _load();
    } catch (e) {
      if (!mounted) return;
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(content: Text('Unable to react: $e')),
      );
    }
  }

  Future<void> _delete(int commentId) async {
    if (!widget.enableDeletion) return;
    try {
      await widget.client.deleteComment(commentId: commentId);
      await _load();
    } catch (e) {
      if (!mounted) return;
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(content: Text('Unable to delete: $e')),
      );
    }
  }

  @override
  Widget build(BuildContext context) {
    final theme = Theme.of(context);

    return Padding(
      padding: widget.padding,
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Row(
            children: [
              Text(
                'Comments',
                style: theme.textTheme.titleMedium?.copyWith(
                  color: theme.colorScheme.primary,
                  fontWeight: FontWeight.w700,
                ),
              ),
              const Spacer(),
              if (_replyTo != null)
                TextButton(
                  onPressed: () => setState(() => _replyTo = null),
                  child: const Text('Cancel reply'),
                ),
            ],
          ),
          const SizedBox(height: 8),
          _buildComposer(theme),
          const SizedBox(height: 12),
          Expanded(
            child: RefreshIndicator(
              onRefresh: _load,
              child: _buildBody(theme),
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildBody(ThemeData theme) {
    if (_loading) {
      return const Center(child: CircularProgressIndicator());
    }

    if (_error != null) {
      return ListView(
        controller: widget.controller,
        children: [
          Container(
            padding: const EdgeInsets.all(16),
            decoration: BoxDecoration(
              color: theme.colorScheme.errorContainer,
              borderRadius: BorderRadius.circular(12),
            ),
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Text('Unable to load comments', style: theme.textTheme.titleSmall),
                const SizedBox(height: 4),
                Text(_error!, style: theme.textTheme.bodySmall),
                const SizedBox(height: 8),
                ElevatedButton.icon(
                  onPressed: _load,
                  icon: const Icon(Icons.refresh),
                  label: const Text('Retry'),
                ),
              ],
            ),
          ),
        ],
      );
    }

    if (_comments.isEmpty) {
      return ListView(
        controller: widget.controller,
        children: [
          Container(
            padding: const EdgeInsets.all(16),
            decoration: BoxDecoration(
              borderRadius: BorderRadius.circular(12),
              color: theme.colorScheme.surfaceVariant,
            ),
            child: Row(
              children: [
                Icon(Icons.chat_bubble_outline, color: theme.colorScheme.onSurfaceVariant),
                const SizedBox(width: 8),
                Expanded(
                  child: Text(
                    'Be the first to add a comment.',
                    style: theme.textTheme.bodyMedium,
                  ),
                ),
              ],
            ),
          ),
        ],
      );
    }

    return ListView.separated(
      controller: widget.controller,
      physics: const AlwaysScrollableScrollPhysics(),
      itemCount: _comments.length,
      separatorBuilder: (_, __) => const SizedBox(height: 12),
      itemBuilder: (context, index) {
        final comment = _comments[index];
        return _CommentTile(
          comment: comment,
          onReply: () => setState(() => _replyTo = comment.id),
          onReact: _react,
          onDelete: widget.enableDeletion ? _delete : null,
        );
      },
    );
  }

  Widget _buildComposer(ThemeData theme) {
    return DecoratedBox(
      decoration: BoxDecoration(
        color: theme.colorScheme.surface,
        borderRadius: BorderRadius.circular(16),
        border: Border.all(color: theme.dividerColor),
      ),
      child: Padding(
        padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 8),
        child: Row(
          crossAxisAlignment: CrossAxisAlignment.center,
          children: [
            Expanded(
              child: TextField(
                controller: _composer,
                minLines: 1,
                maxLines: 4,
                textInputAction: TextInputAction.send,
                onSubmitted: (_) => _postComment(),
                decoration: InputDecoration(
                  hintText: _replyTo != null ? 'Reply to comment…' : 'Add a comment with emojis, GIF links, or stickers…',
                  border: InputBorder.none,
                ),
              ),
            ),
            const SizedBox(width: 8),
            if (_posting) const SizedBox(width: 18, height: 18, child: CircularProgressIndicator(strokeWidth: 2)),
            if (!_posting)
              IconButton(
                onPressed: _postComment,
                icon: Icon(Icons.send_rounded, color: theme.colorScheme.primary),
              ),
          ],
        ),
      ),
    );
  }
}

class _CommentTile extends StatelessWidget {
  const _CommentTile({
    required this.comment,
    required this.onReply,
    required this.onReact,
    this.onDelete,
  });

  final GigvoraComment comment;
  final VoidCallback onReply;
  final void Function(int commentId, String reaction) onReact;
  final void Function(int commentId)? onDelete;

  @override
  Widget build(BuildContext context) {
    final theme = Theme.of(context);

    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        Row(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            CircleAvatar(
              radius: 18,
              backgroundImage: comment.user.photo != null && comment.user.photo!.isNotEmpty
                  ? NetworkImage(comment.user.photo!)
                  : null,
              child: (comment.user.photo == null || comment.user.photo!.isEmpty)
                  ? Text(comment.user.name.isNotEmpty ? comment.user.name[0].toUpperCase() : '?')
                  : null,
            ),
            const SizedBox(width: 12),
            Expanded(
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Row(
                    children: [
                      Expanded(
                        child: Text(
                          comment.user.name,
                          style: theme.textTheme.titleSmall?.copyWith(fontWeight: FontWeight.w700),
                        ),
                      ),
                      Text(comment.created, style: theme.textTheme.bodySmall),
                    ],
                  ),
                  const SizedBox(height: 4),
                  Text(comment.description, style: theme.textTheme.bodyMedium),
                  const SizedBox(height: 8),
                  _ReactionBar(
                    counts: comment.reactionCounts,
                    selected: comment.userReaction,
                    onReact: (reaction) => onReact(comment.id, reaction),
                  ),
                  const SizedBox(height: 8),
                  Row(
                    children: [
                      TextButton.icon(
                        onPressed: onReply,
                        icon: const Icon(Icons.reply, size: 16),
                        label: const Text('Reply'),
                      ),
                      if (onDelete != null)
                        TextButton.icon(
                          onPressed: () => onDelete!(comment.id),
                          icon: const Icon(Icons.delete_outline, size: 16),
                          label: const Text('Delete'),
                        ),
                    ],
                  ),
                  if (comment.replies.isNotEmpty) ...[
                    const SizedBox(height: 8),
                    Column(
                      children: comment.replies
                          .map(
                            (reply) => Padding(
                              padding: const EdgeInsets.only(left: 32, bottom: 12),
                              child: _ReplyTile(
                                comment: reply,
                                onReact: onReact,
                                onDelete: onDelete,
                                onReply: onReply,
                              ),
                            ),
                          )
                          .toList(),
                    )
                  ],
                ],
              ),
            ),
          ],
        ),
      ],
    );
  }
}

class _ReplyTile extends StatelessWidget {
  const _ReplyTile({
    required this.comment,
    required this.onReply,
    required this.onReact,
    this.onDelete,
  });

  final GigvoraComment comment;
  final VoidCallback onReply;
  final void Function(int commentId, String reaction) onReact;
  final void Function(int commentId)? onDelete;

  @override
  Widget build(BuildContext context) {
    final theme = Theme.of(context);

    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        Row(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            CircleAvatar(
              radius: 14,
              backgroundImage: comment.user.photo != null && comment.user.photo!.isNotEmpty
                  ? NetworkImage(comment.user.photo!)
                  : null,
              child: (comment.user.photo == null || comment.user.photo!.isEmpty)
                  ? Text(comment.user.name.isNotEmpty ? comment.user.name[0].toUpperCase() : '?', style: theme.textTheme.labelMedium)
                  : null,
            ),
            const SizedBox(width: 10),
            Expanded(
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Row(
                    children: [
                      Expanded(
                        child: Text(
                          comment.user.name,
                          style: theme.textTheme.bodyMedium?.copyWith(fontWeight: FontWeight.w700),
                        ),
                      ),
                      Text(comment.created, style: theme.textTheme.bodySmall),
                    ],
                  ),
                  const SizedBox(height: 4),
                  Text(comment.description, style: theme.textTheme.bodyMedium),
                  const SizedBox(height: 6),
                  _ReactionBar(
                    counts: comment.reactionCounts,
                    selected: comment.userReaction,
                    onReact: (reaction) => onReact(comment.id, reaction),
                  ),
                  const SizedBox(height: 6),
                  Row(
                    children: [
                      TextButton(
                        onPressed: onReply,
                        child: const Text('Reply'),
                      ),
                      if (onDelete != null)
                        TextButton(
                          onPressed: () => onDelete!(comment.id),
                          child: const Text('Delete'),
                        ),
                    ],
                  ),
                ],
              ),
            ),
          ],
        ),
      ],
    );
  }
}

class _ReactionBar extends StatelessWidget {
  const _ReactionBar({
    required this.counts,
    required this.selected,
    required this.onReact,
  });

  final GigvoraReactionCounts counts;
  final String? selected;
  final void Function(String reaction) onReact;

  static const _reactions = {
    'like': '👍',
    'love': '❤️',
    'haha': '😂',
    'sad': '😢',
    'angry': '😡',
  };

  @override
  Widget build(BuildContext context) {
    final theme = Theme.of(context);
    final chips = <Widget>[];

    void addChip(String label, int value) {
      if (value <= 0) return;
      chips.add(
        Chip(
          materialTapTargetSize: MaterialTapTargetSize.shrinkWrap,
          label: Text('$label $value'),
          backgroundColor: theme.colorScheme.surfaceVariant,
        ),
      );
    }

    addChip('👍', counts.like);
    addChip('❤️', counts.love);
    addChip('😂', counts.haha);
    addChip('😢', counts.sad);
    addChip('😡', counts.angry);

    return Row(
      children: [
        PopupMenuButton<String>(
          tooltip: 'React',
          onSelected: onReact,
          itemBuilder: (context) {
            return [
              ..._reactions.entries
                  .map(
                    (entry) => PopupMenuItem<String>(
                      value: entry.key,
                      child: Row(
                        children: [
                          Text(entry.value),
                          const SizedBox(width: 8),
                          Text(entry.key),
                        ],
                      ),
                    ),
                  )
                  .toList(),
              const PopupMenuItem<String>(
                value: 'none',
                child: Text('Remove reaction'),
              ),
            ];
          },
          child: Row(
            mainAxisSize: MainAxisSize.min,
            children: [
              Text(selected != null && _reactions.containsKey(selected!) ? _reactions[selected!]! : 'React'),
              const Icon(Icons.arrow_drop_down),
            ],
          ),
        ),
        const SizedBox(width: 8),
        if (chips.isNotEmpty)
          Expanded(
            child: Wrap(
              spacing: 6,
              runSpacing: 4,
              children: chips,
            ),
          ),
        if (chips.isEmpty)
          Text(
            'No reactions yet',
            style: theme.textTheme.bodySmall,
          ),
      ],
    );
  }
}
