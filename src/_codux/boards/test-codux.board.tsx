import './test-codux.board.css';
import { createBoard } from '@wixc3/react-board';

export default createBoard({
    name: 'testCodux',
    Board: () => <div>
<div />
</div>,
    isSnippet: true,
});